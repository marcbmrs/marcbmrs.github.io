<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

function voltar(int $obraId, string $erro = ''): never
{
    $url = $erro === '' ? 'obras.php' : 'atualizar-obra.php?id=' . $obraId . '&erro=' . rawurlencode($erro);
    header('Location: ' . $url);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') voltar(0);
$obraId = (int) ($_POST['obra_id'] ?? 0);
$atualizacaoId = (int) ($_POST['atualizacao_id'] ?? 0);
$resumo = trim((string) ($_POST['resumo'] ?? ''));
$data = (string) ($_POST['data_atualizacao'] ?? '');
$status = (string) ($_POST['status'] ?? 'em_andamento');
$proximo = trim((string) ($_POST['proximo_passo'] ?? ''));
$etapa = (string) ($_POST['etapa_fotos'] ?? 'durante');
if ($obraId < 1 || $resumo === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data) || !in_array($status, ['em_andamento', 'pausada', 'concluida'], true) || !in_array($etapa, ['antes', 'durante', 'depois'], true)) voltar($obraId, 'Preencha os dados obrigatórios da atualização.');

try {
    $pdo = banco();
    $consulta = $pdo->prepare('SELECT id FROM obras_acompanhamento WHERE id = ?');
    $consulta->execute([$obraId]);
    if (!$consulta->fetchColumn()) voltar(0, 'Obra não encontrada.');
    $arquivos = $_FILES['fotos'] ?? null;
    $quantidade = $arquivos && is_array($arquivos['name']) ? count(array_filter($arquivos['name'])) : 0;
    if ($quantidade > 5) voltar($obraId, 'Selecione no máximo cinco fotos por atualização.');
    $diretorio = diretorioFotosObras();
    if ($quantidade && !is_dir($diretorio) && !mkdir($diretorio, 0775, true) && !is_dir($diretorio)) throw new RuntimeException('Não foi possível preparar o local seguro das fotos.');
    $pdo->beginTransaction();
    if ($atualizacaoId) {
        $existente = $pdo->prepare('SELECT id FROM atualizacoes_obra WHERE id = ? AND obra_id = ? FOR UPDATE');
        $existente->execute([$atualizacaoId, $obraId]);
        if (!$existente->fetchColumn()) throw new RuntimeException('Atualização não encontrada.');
        $contador = $pdo->prepare('SELECT COUNT(*) FROM fotos_atualizacao_obra WHERE atualizacao_id = ?');
        $contador->execute([$atualizacaoId]);
        if ((int) $contador->fetchColumn() + $quantidade > 5) throw new RuntimeException('Esta atualização já possui fotos demais. Remova uma antes de adicionar outra.');
        $editar = $pdo->prepare('UPDATE atualizacoes_obra SET data_atualizacao = ?, status = ?, resumo = ?, proximo_passo = ? WHERE id = ?');
        $editar->execute([$data, $status, $resumo, $proximo ?: null, $atualizacaoId]);
    } else {
        $inserir = $pdo->prepare('INSERT INTO atualizacoes_obra (obra_id, data_atualizacao, status, resumo, proximo_passo) VALUES (?, ?, ?, ?, ?)');
        $inserir->execute([$obraId, $data, $status, $resumo, $proximo ?: null]);
        $atualizacaoId = (int) $pdo->lastInsertId();
    }
    $foto = $pdo->prepare('INSERT INTO fotos_atualizacao_obra (atualizacao_id, etapa, arquivo, descricao) VALUES (?, ?, ?, ?)');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    for ($indice = 0; $indice < $quantidade; $indice++) {
        if ($arquivos['error'][$indice] !== UPLOAD_ERR_OK || $arquivos['size'][$indice] > 8 * 1024 * 1024) throw new RuntimeException('Uma das fotos é inválida ou maior que 8 MB.');
        $mime = $finfo->file($arquivos['tmp_name'][$indice]);
        if (!isset($tipos[$mime])) throw new RuntimeException('Envie fotos em JPG, PNG ou WebP.');
        $nome = bin2hex(random_bytes(20)) . '.' . $tipos[$mime];
        if (!move_uploaded_file($arquivos['tmp_name'][$indice], $diretorio . '/' . $nome)) throw new RuntimeException('Não foi possível salvar uma das fotos.');
        $foto->execute([$atualizacaoId, $etapa, $nome, 'Foto da atualização de ' . date('d/m/Y', strtotime($data))]);
    }
    $maisRecente = $pdo->prepare('SELECT status, proximo_passo FROM atualizacoes_obra WHERE obra_id = ? ORDER BY data_atualizacao DESC, id DESC LIMIT 1');
    $maisRecente->execute([$obraId]); $referencia = $maisRecente->fetch();
    $pdo->prepare('UPDATE obras_acompanhamento SET status = ?, proxima_etapa = ?, atualizado_em = NOW() WHERE id = ?')->execute([$referencia['status'], $referencia['proximo_passo'], $obraId]);
    $pdo->commit();
    voltar($obraId);
} catch (Throwable $erro) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    voltar($obraId, 'Não foi possível publicar a atualização. Confira as fotos e tente novamente.');
}

