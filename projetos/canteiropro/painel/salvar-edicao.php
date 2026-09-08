<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';
function telefoneBrasil(string $telefone): ?string { $numero = preg_replace('/\D+/', '', $telefone); if (preg_match('/^\d{10,11}$/', $numero)) $numero = '55' . $numero; return preg_match('/^55\d{2}9\d{8}$/', $numero) ? $numero : null; }
try {
    $dados = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    $id = (int) ($dados['id'] ?? 0); $cliente = $dados['cliente'] ?? []; $itens = $dados['itens'] ?? [];
    $telefone = telefoneBrasil((string) ($cliente['telefone'] ?? ''));
    $cidade = trim((string) ($cliente['cidade'] ?? '')); $bairro = trim((string) ($cliente['bairro'] ?? '')); $prazoDias = (int) ($cliente['prazo_dias'] ?? 0);
    if ($id < 1 || trim((string) ($cliente['nome'] ?? '')) === '' || !$telefone || $cidade === '' || $bairro === '' || $prazoDias < 1 || !$itens) throw new RuntimeException('Informe o cliente, um WhatsApp válido com DDD, cidade, bairro, prazo da obra e ao menos um item no orçamento.');
    $pdo = banco(); $pdo->beginTransaction();
    $consulta = $pdo->prepare('SELECT cliente_id FROM orcamentos WHERE id = ? AND status IN ("rascunho", "pronto_para_envio") FOR UPDATE'); $consulta->execute([$id]); $clienteId = (int) $consulta->fetchColumn();
    if (!$clienteId) throw new RuntimeException('Este rascunho não está mais disponível para edição.');
    $pdo->prepare('UPDATE clientes SET nome = ?, telefone = ? WHERE id = ?')->execute([trim((string) $cliente['nome']), $telefone, $clienteId]);
    $pdo->prepare('UPDATE orcamentos SET local_obra = ?, cidade = ?, bairro = ?, endereco = ?, complemento = ?, cep = ?, validade = ?, prazo_dias = ? WHERE id = ?')->execute([$cidade . ' · ' . $bairro, $cidade, $bairro, trim((string) ($cliente['endereco'] ?? '')) ?: null, trim((string) ($cliente['complemento'] ?? '')) ?: null, trim((string) ($cliente['cep'] ?? '')) ?: null, trim((string) ($cliente['validade'] ?? '')) ?: null, $prazoDias, $id]);
    $existentes = $pdo->prepare('SELECT id FROM itens_orcamento WHERE orcamento_id = ? FOR UPDATE'); $existentes->execute([$id]); $idsExistentes = array_flip(array_map('intval', array_column($existentes->fetchAll(), 'id'))); $idsRecebidos = [];
    $atualizar = $pdo->prepare('UPDATE itens_orcamento SET categoria=?, descricao=?, unidade=?, quantidade=?, periodo_quantidade=?, periodo_tipo=?, valor_tabela_centavos=?, valor_unitario_centavos=?, subtotal_centavos=? WHERE id=? AND orcamento_id=?');
    $inserir = $pdo->prepare('INSERT INTO itens_orcamento (orcamento_id,categoria,descricao,unidade,quantidade,periodo_quantidade,periodo_tipo,valor_tabela_centavos,valor_unitario_centavos,subtotal_centavos) VALUES (?,?,?,?,?,?,?,?,?,?)'); $total = 0;
    foreach ($itens as $item) {
        $categoria = ($item['categoria'] ?? '') === 'equipamento' ? 'equipamento' : 'mao_de_obra'; $descricao = trim((string) ($item['nome'] ?? '')); $unidade = trim((string) ($item['unidade'] ?? '')); $quantidade = max(0.01, (float) ($item['quantidade'] ?? 0)); $tipo = in_array($item['periodo_tipo'] ?? null, ['diaria', 'mensal', 'peca'], true) ? $item['periodo_tipo'] : null; $periodo = $tipo === null || $tipo === 'peca' ? null : max(1, (float) ($item['periodo'] ?? 1)); $valor = max(0, (int) ($item['valor_centavos'] ?? 0));
        if ($descricao === '' || $unidade === '') throw new RuntimeException('Há um item incompleto no orçamento.');
        $valorTabela = max($valor, (int) ($item['valor_tabela_centavos'] ?? $valor)); $subtotal = (int) round($quantidade * ($periodo ?? 1) * $valor); $total += $subtotal; $valores = [$categoria, $descricao, $unidade, $quantidade, $periodo, $tipo, $valorTabela, $valor, $subtotal]; $itemId = (int) ($item['id'] ?? 0);
        if ($itemId && isset($idsExistentes[$itemId]) && !isset($idsRecebidos[$itemId])) { $atualizar->execute([...$valores, $itemId, $id]); $idsRecebidos[$itemId] = true; } else { $inserir->execute([$id, ...$valores]); }
    }
    $remover = array_diff_key($idsExistentes, $idsRecebidos); if ($remover) { $marcadores = implode(',', array_fill(0, count($remover), '?')); $pdo->prepare("DELETE FROM itens_orcamento WHERE orcamento_id = ? AND id IN ($marcadores)")->execute([$id, ...array_keys($remover)]); }
    $pdo->prepare('UPDATE orcamentos SET total_centavos = ? WHERE id = ?')->execute([$total, $id]); $pdo->commit(); echo json_encode(['ok' => true, 'redirect' => 'orcamento.php?id=' . $id]);
} catch (Throwable $erro) { if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack(); http_response_code(422); echo json_encode(['ok' => false, 'erro' => $erro->getMessage()]); }

