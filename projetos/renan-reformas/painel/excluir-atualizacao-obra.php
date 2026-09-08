<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$obraId = (int) ($_POST['obra_id'] ?? 0);
$atualizacaoId = (int) ($_POST['atualizacao_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $obraId < 1 || $atualizacaoId < 1) { header('Location: obras.php'); exit; }
try {
    $pdo = banco(); $pdo->beginTransaction();
    $consulta = $pdo->prepare('SELECT f.arquivo FROM fotos_atualizacao_obra f JOIN atualizacoes_obra a ON a.id = f.atualizacao_id WHERE a.id = ? AND a.obra_id = ?');
    $consulta->execute([$atualizacaoId, $obraId]); $arquivos = $consulta->fetchAll(PDO::FETCH_COLUMN);
    $remover = $pdo->prepare('DELETE FROM atualizacoes_obra WHERE id = ? AND obra_id = ?'); $remover->execute([$atualizacaoId, $obraId]);
    $maisRecente = $pdo->prepare('SELECT status, proximo_passo FROM atualizacoes_obra WHERE obra_id = ? ORDER BY data_atualizacao DESC, id DESC LIMIT 1'); $maisRecente->execute([$obraId]); $referencia = $maisRecente->fetch();
    if ($referencia) $pdo->prepare('UPDATE obras_acompanhamento SET status = ?, proxima_etapa = ?, atualizado_em = NOW() WHERE id = ?')->execute([$referencia['status'], $referencia['proximo_passo'], $obraId]);
    $pdo->commit();
    foreach ($arquivos as $arquivo) { $caminho = diretorioFotosObras() . '/' . $arquivo; if ($arquivo && is_file($caminho)) unlink($caminho); }
} catch (Throwable) { if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack(); }
header('Location: atualizar-obra.php?id=' . $obraId); exit;
