<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: orcamentos.php'); exit; }
$orcamentoId = (int) ($_POST['orcamento_id'] ?? 0);
if ($orcamentoId < 1) { header('Location: orcamentos.php'); exit; }

try {
    $pdo = banco();
    $consulta = $pdo->prepare('SELECT o.*, c.nome FROM orcamentos o JOIN clientes c ON c.id = o.cliente_id WHERE o.id = ? AND o.status = "aprovado"');
    $consulta->execute([$orcamentoId]);
    $orcamento = $consulta->fetch();
    if (!$orcamento) throw new RuntimeException('Este orçamento não está aprovado ou não foi encontrado.');

    $existente = $pdo->prepare('SELECT id FROM obras_acompanhamento WHERE orcamento_id = ?');
    $existente->execute([$orcamentoId]);
    $obraExistente = (int) $existente->fetchColumn();
    if ($obraExistente) { header('Location: obras.php?obra=' . $obraExistente); exit; }

    $codigo = (string) random_int(100000, 999999);
    $token = bin2hex(random_bytes(32));
    $titulo = 'Obra de ' . $orcamento['nome'];
    $localidade = $orcamento['local_obra'] ?: trim(($orcamento['bairro'] ?? '') . ' · ' . ($orcamento['cidade'] ?? ''));
    $inserir = $pdo->prepare('INSERT INTO obras_acompanhamento (orcamento_id, titulo, cliente_nome, localidade, status, proxima_etapa, token_acesso, codigo_acesso_hash, codigo_acesso_exibicao) VALUES (?, ?, ?, ?, "em_andamento", ?, ?, ?, ?)');
    $inserir->execute([$orcamentoId, $titulo, $orcamento['nome'], $localidade ?: null, 'Registrar a primeira atualização da obra.', $token, password_hash($codigo, PASSWORD_DEFAULT), $codigo]);
    header('Location: atualizar-obra.php?id=' . (int) $pdo->lastInsertId());
} catch (Throwable $erro) {
    http_response_code(422);
    echo htmlspecialchars($erro->getMessage(), ENT_QUOTES, 'UTF-8');
}
