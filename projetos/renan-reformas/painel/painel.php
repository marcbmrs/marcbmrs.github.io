<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$pdo = banco();
$totalOrcamentos = (int) $pdo->query('SELECT COUNT(*) FROM orcamentos')->fetchColumn();
$orcamentosAtivos = (int) $pdo->query("SELECT COUNT(*) FROM orcamentos WHERE status IN ('rascunho', 'pronto_para_envio', 'aguardando_cliente', 'negociacao')")->fetchColumn();
$orcamentosAprovados = (int) $pdo->query("SELECT COUNT(*) FROM orcamentos WHERE status = 'aprovado'")->fetchColumn();
$obrasAndamento = (int) $pdo->query("SELECT COUNT(*) FROM obras_acompanhamento WHERE status = 'em_andamento'")->fetchColumn();
$proximasAcoes = $pdo->query(
    "SELECT o.id, o.codigo, o.status, o.atualizado_em, c.nome AS cliente
     FROM orcamentos o
     JOIN clientes c ON c.id = o.cliente_id
     WHERE o.status IN ('rascunho', 'pronto_para_envio', 'aguardando_cliente', 'negociacao')
     ORDER BY FIELD(o.status, 'pronto_para_envio', 'aguardando_cliente', 'negociacao', 'rascunho'), o.atualizado_em DESC
     LIMIT 5"
)->fetchAll();

function esc(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function acaoOrcamento(array $orcamento): array
{
    return [
        'rascunho' => ['Continuar orçamento', 'editar.php?id=' . (int) $orcamento['id'], 'Rascunho salvo'],
        'pronto_para_envio' => ['Enviar proposta', 'orcamento.php?id=' . (int) $orcamento['id'], 'Pronto para cliente'],
        'aguardando_cliente' => ['Retomar cliente', 'orcamento.php?id=' . (int) $orcamento['id'], 'Aguardando resposta'],
        'negociacao' => ['Ajustar revisão', 'orcamento.php?id=' . (int) $orcamento['id'], 'Em negociação'],
    ][$orcamento['status']] ?? ['Abrir orçamento', 'orcamento.php?id=' . (int) $orcamento['id'], 'Em andamento'];
}
?>
<!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#151515"><title>Painel | Renan Reformas</title><link rel="stylesheet" href="styles/main.css"></head>
<body class="dashboard-page"><main class="app-shell dashboard-shell">
  <header class="topbar"><a class="brand" href="painel.php" aria-label="Início do painel"><span class="brand-mark" aria-hidden="true"></span><span>RENAN <b>REFORMAS</b></span></a><form class="panel-exit-form" method="post" action="sair.php"><?= campoCsrf() ?><button class="panel-exit" type="submit">Sair</button></form></header>
  <section class="dashboard-heading"><p class="eyebrow">Painel de trabalho</p><h1>O que você precisa fazer hoje?</h1><p>Centralize os orçamentos, acompanhe as obras e mantenha cada cliente informado.</p></section>

  <section class="today-actions" aria-labelledby="today-actions-title">
    <div class="today-actions-heading">
      <div><p class="eyebrow">Próximas ações</p><h2 id="today-actions-title">Retomar trabalho</h2></div>
      <a href="orcamentos.php">Ver todos</a>
    </div>
    <?php if ($proximasAcoes): ?>
      <div class="today-action-list">
        <?php foreach ($proximasAcoes as $orcamento): [$rotuloAcao, $linkAcao, $rotuloStatus] = acaoOrcamento($orcamento); ?>
          <a class="today-action-card" href="<?= esc($linkAcao) ?>">
            <span class="today-action-status status-<?= esc($orcamento['status']) ?>"><?= esc($rotuloStatus) ?></span>
            <strong><?= esc($orcamento['cliente']) ?></strong>
            <small><?= esc($orcamento['codigo']) ?></small>
            <b><?= esc($rotuloAcao) ?> <span aria-hidden="true">›</span></b>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="today-action-empty">
        <strong>Nada pendente agora.</strong>
        <p>Quando houver rascunhos, propostas prontas ou negociações, elas aparecem aqui.</p>
      </div>
    <?php endif; ?>
  </section>
  <section class="dashboard-overview" aria-labelledby="overview-title"><div class="overview-heading"><div><p class="eyebrow">Visão geral</p><h2 id="overview-title">Seu painel hoje</h2></div><span class="overview-dashboard-icon" aria-hidden="true"><i></i><i></i><i></i><i></i></span></div><div class="overview-stats"><a href="orcamentos.php"><strong><?= $orcamentosAtivos ?></strong><span>propostas<br>ativas</span></a><a href="obras.php"><strong><?= $obrasAndamento ?></strong><span>obras em<br>andamento</span></a><a href="orcamentos.php"><strong><?= $orcamentosAprovados ?></strong><span>orçamentos<br>aprovados</span></a></div><a class="overview-cta" href="index.php">Criar orçamento <span aria-hidden="true">→</span></a></section>
  <section class="dashboard-grid" aria-label="Áreas do painel">
    <a class="dashboard-card" href="orcamentos.php"><picture class="dashboard-asset"><source type="image/avif" srcset="assets/images/icone-orcamentos-128w.avif 128w, assets/images/icone-orcamentos-256w.avif 256w" sizes="52px"><img src="assets/images/icone-orcamentos-128w.webp" width="52" height="52" alt=""></picture><div><p class="eyebrow">Comercial</p><h2>Orçamentos</h2><p>Consulte, edite, envie e acompanhe as propostas.</p></div><strong><?= $orcamentosAtivos ?> <small><?= $orcamentosAtivos === 1 ? 'ativo' : 'ativos' ?></small></strong><span class="dashboard-arrow" aria-hidden="true">›</span></a>
    <a class="dashboard-card" href="obras.php"><picture class="dashboard-asset"><source type="image/avif" srcset="assets/images/icone-obras-128w.avif 128w, assets/images/icone-obras-256w.avif 256w" sizes="52px"><img src="assets/images/icone-obras-128w.webp" width="52" height="52" alt=""></picture><div><p class="eyebrow">Execução</p><h2>Obras</h2><p>Publique atualizações e acompanhe a página privada de cada cliente.</p></div><strong><?= $obrasAndamento ?> <small>em andamento</small></strong><span class="dashboard-arrow" aria-hidden="true">›</span></a>
    <a class="dashboard-card" href="contratos.php"><picture class="dashboard-asset"><source type="image/avif" srcset="assets/images/icone-contratos-128w.avif 128w, assets/images/icone-contratos-256w.avif 256w" sizes="52px"><img src="assets/images/icone-contratos-128w.webp" width="52" height="52" alt=""></picture><div><p class="eyebrow">Formalização</p><h2>Contratos</h2><p>Prepare o contrato a partir de um orçamento aprovado.</p></div><strong><small>Em preparação</small></strong><span class="dashboard-arrow" aria-hidden="true">›</span></a>
  </section>
</main><?php $activeNav = 'inicio'; require __DIR__ . '/app/nav-painel.php'; ?></body></html>



