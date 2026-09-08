<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';
$conteudo = trim((string) ($_POST['conteudo'] ?? ''));
if ($conteudo === '') { http_response_code(422); exit('Modelo-base inválido.'); }
$salvar = banco()->prepare('INSERT INTO configuracoes_contrato (chave, valor) VALUES ("modelo_base", ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)');
$salvar->execute([$conteudo]);
header('Location: editar-modelo-contrato.php?salvo=1');
