<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

function telefoneBrasil(string $telefone): ?string
{
    $numero = preg_replace('/\D+/', '', $telefone);
    if (preg_match('/^\d{10,11}$/', $numero)) $numero = '55' . $numero;
    return preg_match('/^55\d{2}9\d{8}$/', $numero) ? $numero : null;
}

try {
    $dados = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    $telefone = telefoneBrasil((string) ($dados['cliente']['telefone'] ?? ''));
    $cidade = trim((string) ($dados['cliente']['cidade'] ?? '')); $bairro = trim((string) ($dados['cliente']['bairro'] ?? '')); $prazoDias = (int) ($dados['cliente']['prazo_dias'] ?? 0);
    if (trim($dados['cliente']['nome'] ?? '') === '' || !$telefone || $cidade === '' || $bairro === '' || $prazoDias < 1 || empty($dados['itens'])) throw new RuntimeException('Informe o cliente, um WhatsApp válido com DDD, cidade, bairro, prazo da obra e ao menos um item.');
    $pdo = banco(); $pdo->beginTransaction();
    $cliente = trim($dados['cliente']['nome']);
    $stmt = $pdo->prepare('INSERT INTO clientes (nome, telefone) VALUES (?, ?)'); $stmt->execute([$cliente, $telefone]); $clienteId = (int) $pdo->lastInsertId();
    $codigo = 'ORC-' . date('Ymd-His'); $total = 0;
    $stmt = $pdo->prepare('INSERT INTO orcamentos (codigo, cliente_id, local_obra, cidade, bairro, endereco, complemento, cep, validade, prazo_dias, total_centavos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$codigo, $clienteId, $cidade . ' · ' . $bairro, $cidade, $bairro, trim((string) ($dados['cliente']['endereco'] ?? '')) ?: null, trim((string) ($dados['cliente']['complemento'] ?? '')) ?: null, trim((string) ($dados['cliente']['cep'] ?? '')) ?: null, trim((string) ($dados['cliente']['validade'] ?? '')) ?: null, $prazoDias, $total]); $orcamentoId = (int) $pdo->lastInsertId();
    $itemStmt = $pdo->prepare('INSERT INTO itens_orcamento (orcamento_id,categoria,descricao,unidade,quantidade,periodo_quantidade,periodo_tipo,valor_tabela_centavos,valor_unitario_centavos,subtotal_centavos) VALUES (?,?,?,?,?,?,?,?,?,?)');
    foreach ($dados['itens'] as $item) {
        $quantidade = max(0.01, (float) ($item['quantidade'] ?? 0));
        $periodo = $item['periodo'] ?? null;
        $periodo = $periodo === null ? null : max(1, (float) $periodo);
        $valorNegociado = max(0, (int) ($item['valor_centavos'] ?? 0));
        $valorTabela = max($valorNegociado, (int) ($item['valor_tabela_centavos'] ?? $valorNegociado));
        $subtotal = (int) round($quantidade * ($periodo ?? 1) * $valorNegociado);
        $total += $subtotal;
        $itemStmt->execute([$orcamentoId, $item['categoria'], $item['nome'], $item['unidade'], $quantidade, $periodo, $item['periodo_tipo'] ?? null, $valorTabela, $valorNegociado, $subtotal]);
    }
    $pdo->prepare('UPDATE orcamentos SET total_centavos = ? WHERE id = ?')->execute([$total, $orcamentoId]);
    $pdo->commit(); echo json_encode(['ok' => true, 'codigo' => $codigo]);
} catch (Throwable $erro) { if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack(); http_response_code(422); echo json_encode(['ok' => false, 'erro' => $erro->getMessage()]); }
