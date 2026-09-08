<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/app/db.php';

$id = (int) ($_GET['id'] ?? 0);
$consulta = banco()->prepare('SELECT f.arquivo FROM fotos_atualizacao_obra f JOIN atualizacoes_obra a ON a.id = f.atualizacao_id WHERE f.id = ?');
$consulta->execute([$id]);
$arquivo = $consulta->fetchColumn();
if (!$arquivo || !preg_match('/^(?:[a-f0-9]{40}|demo-[a-z0-9-]+)\.(jpg|png|webp)$/', $arquivo)) { http_response_code(404); exit; }
$permitido = false;
$obra = banco()->prepare('SELECT a.obra_id FROM fotos_atualizacao_obra f JOIN atualizacoes_obra a ON a.id = f.atualizacao_id WHERE f.id = ?');
$obra->execute([$id]);
$obraId = (int) $obra->fetchColumn();
if (!empty($_SESSION['obra_acesso_' . $obraId])) $permitido = true;
if (!$permitido) { http_response_code(403); exit; }
$caminho = diretorioFotosObras() . '/' . $arquivo;
if (!is_file($caminho)) { http_response_code(404); exit; }
$mime = mime_content_type($caminho) ?: 'application/octet-stream';
header('Content-Type: ' . $mime); header('Content-Length: ' . filesize($caminho)); header('Cache-Control: private, max-age=86400');
readfile($caminho);

