<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$obraId = (int) ($_POST['obra_id'] ?? 0); $fotoId = (int) ($_POST['foto_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $obraId < 1 || $fotoId < 1) { header('Location: obras.php'); exit; }
$editar = (int) ($_POST['atualizacao_id'] ?? 0);
try {
    $pdo = banco(); $consulta = $pdo->prepare('SELECT f.arquivo, f.atualizacao_id FROM fotos_atualizacao_obra f JOIN atualizacoes_obra a ON a.id = f.atualizacao_id WHERE f.id = ? AND a.obra_id = ?'); $consulta->execute([$fotoId, $obraId]); $foto = $consulta->fetch();
    if ($foto) { $pdo->prepare('DELETE FROM fotos_atualizacao_obra WHERE id = ?')->execute([$fotoId]); $caminho = diretorioFotosObras() . '/' . $foto['arquivo']; if ($foto['arquivo'] && is_file($caminho)) unlink($caminho); $editar = (int) $foto['atualizacao_id']; }
} catch (Throwable) { }
header('Location: atualizar-obra.php?id=' . $obraId . '&editar=' . $editar); exit;

