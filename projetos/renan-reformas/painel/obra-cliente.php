<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/app/db.php';

function esc(string $texto): string { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
function statusObra(string $status): string { return ['em_andamento' => 'Em andamento', 'pausada' => 'Pausada', 'concluida' => 'Concluída'][$status] ?? 'Atualização'; }

$token = trim((string) ($_GET['acesso'] ?? ''));
$basePublica = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$imagemCompartilhamento = $basePublica . '/assets/images/og-acompanhamento-v3.png';
$consulta = banco()->prepare('SELECT * FROM obras_acompanhamento WHERE token_acesso = ?');
$consulta->execute([$token]);
$obra = $consulta->fetch();
if (!$obra) { http_response_code(404); exit('Página da obra não encontrada.'); }
$chaveSessao = 'obra_acesso_' . $obra['id'];
$erro = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (password_verify((string) ($_POST['codigo'] ?? ''), $obra['codigo_acesso_hash'])) {
        $_SESSION[$chaveSessao] = true;
        header('Location: obra-cliente.php?acesso=' . rawurlencode($token));
        exit;
    }
    $erro = true;
}
$autorizado = !empty($_SESSION[$chaveSessao]);
if ($autorizado) {
    $consulta = banco()->prepare('SELECT * FROM atualizacoes_obra WHERE obra_id = ? ORDER BY data_atualizacao DESC, id DESC');
    $consulta->execute([$obra['id']]);
    $atualizacoes = $consulta->fetchAll();
    $totalAtualizacoes = count($atualizacoes);
    $ultimaAtualizacao = $atualizacoes[0]['data_atualizacao'] ?? null;
    $fotos = banco()->prepare('SELECT * FROM fotos_atualizacao_obra WHERE atualizacao_id = ? ORDER BY id');
}
?>
<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#151515"><meta name="robots" content="noindex, nofollow"><title>Acompanhamento da obra | Renan Reformas</title><meta property="og:type" content="website"><meta property="og:title" content="Acompanhamento da obra | Renan Reformas"><meta property="og:description" content="Confira as atualizações privadas da sua obra."><meta property="og:image" content="<?= esc($imagemCompartilhamento) ?>"><meta property="og:image:width" content="1200"><meta property="og:image:height" content="630"><meta name="twitter:card" content="summary_large_image"><link rel="stylesheet" href="styles/main.css"></head>
<body class="client-feed-body"><main class="client-feed-shell">
  <header class="client-feed-header"><a class="brand" href="../" aria-label="Site Renan Reformas"><span class="brand-mark" aria-hidden="true"></span><span>RENAN <b>REFORMAS</b></span></a><span class="private-label">Área privada</span></header>
  <?php if (!$autorizado): ?>
    <section class="access-card"><div class="access-card-lock" aria-hidden="true">#</div><p class="eyebrow">Acompanhamento privado</p><h1><?= esc($obra['titulo']) ?></h1><p>Olá, <?= esc($obra['cliente_nome']) ?>. Digite o código enviado pela Renan Reformas para ver o andamento da sua obra.</p><form method="post"><label>Código de acesso<input name="codigo" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}" required autofocus placeholder="000000"></label><?php if ($erro): ?><p class="access-error">Código inválido. Confira e tente novamente.</p><?php endif; ?><button class="primary-action" type="submit">Ver minha obra</button></form><small>Este link é privado. Não compartilhe o código de acesso.</small></section>
  <?php else: ?>
    <section class="client-hero"><div class="client-hero-kicker"><p class="eyebrow">Acompanhamento da obra</p><span class="work-status status-<?= esc($obra['status']) ?>"><?= esc(statusObra($obra['status'])) ?></span></div><div class="client-hero-title"><div><h1><?= esc($obra['titulo']) ?></h1><p><?= esc($obra['localidade'] ?: 'Local da obra') ?></p></div></div><div class="client-summary"><span><small>Cliente</small><strong><?= esc($obra['cliente_nome']) ?></strong></span><span><small>Atualizações</small><strong><?= (int) $totalAtualizacoes ?></strong></span><span><small>Última</small><strong><?= $ultimaAtualizacao ? date('d/m', strtotime($ultimaAtualizacao)) : 'Sem registro' ?></strong></span></div><div class="next-step"><small>Próxima etapa</small><strong><?= esc($obra['proxima_etapa'] ?: 'A confirmar') ?></strong></div></section>
    <section class="feed-intro"><div><h2>Linha do tempo</h2><p>Resumo visual do que já foi registrado na obra.</p></div><span><?= (int) $totalAtualizacoes ?> <?= $totalAtualizacoes === 1 ? 'registro' : 'registros' ?></span></section>
    <section class="work-feed" aria-label="Linha do tempo da obra">
      <?php if (!$atualizacoes): ?>
        <article class="feed-empty"><strong>Nenhuma atualização publicada ainda.</strong><p>Assim que a equipe registrar o primeiro andamento, ele aparecerá aqui.</p></article>
      <?php endif; ?>
      <?php foreach ($atualizacoes as $atualizacao): $fotos->execute([$atualizacao['id']]); $itensFoto = $fotos->fetchAll(); ?>
        <article class="feed-entry"><div class="feed-date"><time datetime="<?= esc($atualizacao['data_atualizacao']) ?>"><?= date('d/m', strtotime($atualizacao['data_atualizacao'])) ?></time><span><?= esc(statusObra($atualizacao['status'])) ?></span></div><div class="feed-content"><div class="feed-entry-heading"><h3><?= date('d/m/Y', strtotime($atualizacao['data_atualizacao'])) ?></h3><span class="feed-status status-<?= esc($atualizacao['status']) ?>"><?= esc(statusObra($atualizacao['status'])) ?></span></div><p><?= esc($atualizacao['resumo']) ?></p><?php if ($itensFoto): ?><div class="feed-photos"><?php foreach ($itensFoto as $foto): ?><?php if ($foto['arquivo']): ?><figure class="real-photo"><button class="photo-zoom" type="button" data-photo-src="foto-obra.php?id=<?= (int) $foto['id'] ?>" data-photo-alt="<?= esc($foto['descricao']) ?>"><img src="foto-obra.php?id=<?= (int) $foto['id'] ?>" alt="<?= esc($foto['descricao']) ?>" loading="lazy"><span aria-hidden="true">Ampliar</span></button><figcaption><?= esc($foto['descricao']) ?></figcaption></figure><?php else: ?><figure class="demo-photo stage-<?= esc($foto['etapa']) ?>"><span><?= esc($foto['etapa']) ?></span><figcaption><?= esc($foto['descricao']) ?></figcaption></figure><?php endif; ?><?php endforeach; ?></div><?php endif; ?><?php if ($atualizacao['proximo_passo']): ?><div class="entry-next"><small>Próximo passo</small><strong><?= esc($atualizacao['proximo_passo']) ?></strong></div><?php endif; ?></div></article>
      <?php endforeach; ?>
    </section>
    <aside class="feed-contact"><span aria-hidden="true">?</span><div><strong>Ficou com alguma dúvida?</strong><p>Fale diretamente com a Renan Reformas para alinhar qualquer detalhe da obra.</p></div></aside>
  <?php endif; ?>
</main><dialog class="photo-lightbox" data-photo-lightbox><button class="photo-lightbox-close" type="button" aria-label="Fechar foto">×</button><img src="" alt="" data-lightbox-image></dialog><script src="scripts/feed-fotos.js"></script></body></html>
