<?php
declare(strict_types=1);

require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$consulta = banco()->query(
    'SELECT o.*, c.nome AS cliente
     FROM orcamentos o
     JOIN clientes c ON c.id = o.cliente_id
     ORDER BY o.atualizado_em DESC'
);
$orcamentos = $consulta->fetchAll();

function esc(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function statusOrcamento(string $status): string
{
    return [
        'rascunho' => 'Rascunho',
        'pronto_para_envio' => 'Pronto para enviar',
        'aguardando_cliente' => 'Aguardando cliente',
        'negociacao' => 'Em negociação',
        'aprovado' => 'Aprovado',
        'recusado' => 'Recusado',
        'expirado' => 'Expirado',
        'cancelado' => 'Cancelado',
    ][$status] ?? ucfirst($status);
}

function acaoRapidaOrcamento(array $orcamento): array
{
    return [
        'rascunho' => ['Continuar', 'editar.php?id=' . (int) $orcamento['id']],
        'pronto_para_envio' => ['Gerar PDF', 'gerar-pdf.php?id=' . (int) $orcamento['id']],
        'aguardando_cliente' => ['Retomar', 'orcamento.php?id=' . (int) $orcamento['id']],
        'negociacao' => ['Criar revisão', 'criar-revisao.php?id=' . (int) $orcamento['id']],
        'aprovado' => ['Criar obra', 'orcamento.php?id=' . (int) $orcamento['id']],
    ][$orcamento['status']] ?? ['Abrir', 'orcamento.php?id=' . (int) $orcamento['id']];
}

function pdfDisponivel(string $status): bool
{
    return in_array($status, ['pronto_para_envio', 'aguardando_cliente', 'negociacao', 'aprovado'], true);
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#151515">
  <title>Meus orçamentos | CanteiroPro</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body class="budgets-page">
  <main class="app-shell budgets-shell">
    <header class="topbar">
      <a class="brand" href="painel.php" aria-label="Ir para o início do painel"><span class="brand-mark" aria-hidden="true"></span><span>CANTEIRO <b>PRO</b></span></a>
    </header>

    <section class="page-heading budgets-heading">
      <p class="eyebrow">Comercial</p>
      <div class="budgets-title-row"><h1>Meus orçamentos</h1><a class="budget-create" href="index.php"><span aria-hidden="true">+</span> Novo</a></div>
      <p>Consulte o histórico ou retome rapidamente um atendimento.</p>
    </section>
    <aside class="demo-notice" aria-label="Aviso de demonstração"><strong>Demo CanteiroPro</strong><span>Os exemplos desta lista usam clientes e obras fictícios.</span></aside>

    <section class="budget-controls" aria-label="Buscar e filtrar orçamentos">
      <label class="budget-search"><span class="sr-only">Buscar orçamento</span><input type="search" data-budget-search placeholder="Buscar por cliente ou código" autocomplete="off"></label>
      <div class="budget-filters" role="group" aria-label="Filtrar por status">
        <button class="filter-chip is-active" type="button" data-filter="todos">Todos <span><?= count($orcamentos) ?></span></button>
        <button class="filter-chip" type="button" data-filter="rascunho">Rascunhos</button>
        <button class="filter-chip" type="button" data-filter="pronto_para_envio">Prontos</button>
        <button class="filter-chip" type="button" data-filter="aguardando_cliente">Aguardando</button>
        <button class="filter-chip" type="button" data-filter="aprovado">Aprovados</button>
      </div>
    </section>

    <section class="budget-list" aria-live="polite" aria-label="Lista de orçamentos">
      <?php foreach ($orcamentos as $orcamento): [$acaoRotulo, $acaoLink] = acaoRapidaOrcamento($orcamento); ?>
        <article class="budget-card" data-budget-card data-status="<?= esc($orcamento['status']) ?>" data-search="<?= esc($orcamento['cliente'] . ' ' . $orcamento['codigo']) ?>">
          <a class="budget-card-open" href="orcamento.php?id=<?= (int) $orcamento['id'] ?>">
            <div class="budget-card-main">
              <strong><?= esc($orcamento['cliente']) ?></strong>
              <span class="budget-code"><?= esc($orcamento['codigo']) ?></span>
            </div>
            <span class="status-badge status-<?= esc($orcamento['status']) ?>"><?= esc(statusOrcamento($orcamento['status'])) ?></span>
            <strong class="budget-total">R$ <?= number_format($orcamento['total_centavos'] / 100, 2, ',', '.') ?></strong>
            <span class="budget-arrow" aria-hidden="true">›</span>
          </a>
          <div class="budget-quick-actions" aria-label="Ações rápidas para <?= esc($orcamento['cliente']) ?>">
            <a class="quick-action primary" href="<?= esc($acaoLink) ?>"><?= esc($acaoRotulo) ?></a>
            <a class="quick-action icon" href="orcamento.php?id=<?= (int) $orcamento['id'] ?>" aria-label="Ver detalhes de <?= esc($orcamento['cliente']) ?>"><span aria-hidden="true">i</span></a>
            <?php if (pdfDisponivel($orcamento['status'])): ?><a class="quick-action icon" href="gerar-pdf.php?id=<?= (int) $orcamento['id'] ?>" target="_blank" rel="noopener" aria-label="Gerar PDF de <?= esc($orcamento['cliente']) ?>"><span aria-hidden="true">PDF</span></a><?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
      <div class="budget-empty" data-empty-results hidden><strong>Nenhum orçamento encontrado.</strong><p>Tente outro nome, código ou filtro.</p></div>
      <?php if (!$orcamentos): ?>
        <div class="budget-empty"><strong>Você ainda não tem orçamentos.</strong><p>Comece um novo atendimento para vê-lo listado aqui.</p><a class="primary-action" href="index.php">Criar orçamento</a></div>
      <?php endif; ?>
    </section>
  </main>
  <?php $activeNav = 'orcamentos'; require __DIR__ . '/app/nav-painel.php'; ?>
  <script src="scripts/orcamentos.js"></script>
</body>
</html>



