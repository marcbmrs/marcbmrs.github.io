<?php
declare(strict_types=1);

require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$id = (int) ($_POST['id'] ?? 0);
$novoStatus = (string) ($_POST['status'] ?? '');
$transicoes = [
    'rascunho' => ['pronto_para_envio', 'cancelado'],
    'pronto_para_envio' => ['rascunho', 'aguardando_cliente', 'cancelado'],
    'aguardando_cliente' => ['negociacao', 'aprovado', 'recusado', 'expirado', 'cancelado'],
    'negociacao' => ['aguardando_cliente', 'aprovado', 'recusado', 'cancelado'],
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $id < 1 || $novoStatus === '') {
    header('Location: orcamentos.php');
    exit;
}
$pdo = banco();
$consulta = $pdo->prepare('SELECT status FROM orcamentos WHERE id = ?');
$consulta->execute([$id]);
$statusAtual = $consulta->fetchColumn();
if (!$statusAtual || !in_array($novoStatus, $transicoes[$statusAtual] ?? [], true)) { http_response_code(422); exit('Esta alteração não é permitida para o status atual.'); }
$pdo->prepare('UPDATE orcamentos SET status = ? WHERE id = ?')->execute([$novoStatus, $id]);
header('Location: orcamento.php?id=' . $id);
