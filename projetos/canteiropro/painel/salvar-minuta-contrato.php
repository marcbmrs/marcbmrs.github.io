<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';
$id = (int) ($_POST['orcamento_id'] ?? 0);
$conteudo = trim((string) ($_POST['conteudo'] ?? ''));
if ($id < 1 || $conteudo === '') { http_response_code(422); exit('Conteúdo da minuta inválido.'); }
$consulta = banco()->prepare('SELECT id FROM orcamentos WHERE id = ? AND status = "aprovado"');
$consulta->execute([$id]);
if (!$consulta->fetchColumn()) { http_response_code(422); exit('Este orçamento não está aprovado.'); }
$salvar = banco()->prepare('INSERT INTO minutas_contrato (orcamento_id, conteudo) VALUES (?, ?) ON DUPLICATE KEY UPDATE conteudo = VALUES(conteudo)');
$salvar->execute([$id, $conteudo]);
header('Location: editar-contrato.php?id=' . $id . '&salvo=1');

