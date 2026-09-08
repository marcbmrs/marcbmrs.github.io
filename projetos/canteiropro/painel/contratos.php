<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$orcamentosAprovados = banco()->query(
    'SELECT o.id, o.codigo, o.total_centavos, o.atualizado_em, c.nome AS cliente, m.id AS minuta_id, m.atualizado_em AS minuta_atualizada_em
     FROM orcamentos o
     JOIN clientes c ON c.id = o.cliente_id
     LEFT JOIN minutas_contrato m ON m.orcamento_id = o.id
     WHERE o.status = "aprovado"
     ORDER BY COALESCE(m.atualizado_em, o.atualizado_em) DESC'
)->fetchAll();
$totalAprovados = count($orcamentosAprovados);
$minutasSalvas = count(array_filter($orcamentosAprovados, static fn (array $orcamento): bool => !empty($orcamento['minuta_id'])));
$minutasPendentes = $totalAprovados - $minutasSalvas;

function esc(string $texto): string { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
function moeda(int $centavos): string { return 'R$ ' . number_format($centavos / 100, 2, ',', '.'); }
function dataCurta(?string $data): string { return $data ? date('d/m/Y', strtotime($data)) : 'Sem data'; }
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#151515">
  <title>Contratos | CanteiroPro</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body class="contracts-app-page">
  <main class="app-shell contracts-page contracts-app-shell">
    <header class="topbar"><a class="brand" href="painel.php" aria-label="Início do painel"><span class="brand-mark" aria-hidden="true"></span><span>CANTEIRO <b>PRO</b></span></a></header>

    <section class="page-heading contracts-heading">
      <p class="eyebrow">Formalização</p>
      <h1>Contratos</h1>
      <p>Transforme orçamentos aprovados em minutas revisáveis antes da assinatura.</p>
    </section>

    <section class="contract-workbench" aria-labelledby="contract-workbench-title">
      <div class="contract-workbench-top">
        <div>
          <p class="eyebrow">Fila de contratos</p>
          <h2 id="contract-workbench-title"><?= $minutasPendentes > 0 ? 'Há minutas para preparar' : 'Tudo organizado por aqui' ?></h2>
          <p><?= $totalAprovados > 0 ? 'Revise os dados, salve a minuta e siga para assinatura física.' : 'Quando um orçamento for aprovado, ele aparece nesta tela.' ?></p>
        </div>
        <picture><source type="image/avif" srcset="assets/images/icone-contratos-128w.avif"><img src="assets/images/icone-contratos-128w.webp" width="46" height="46" alt=""></picture>
      </div>
      <div class="contract-stats" aria-label="Resumo dos contratos">
        <span><strong><?= $totalAprovados ?></strong><small>aprovados</small></span>
        <span><strong><?= $minutasPendentes ?></strong><small>pendentes</small></span>
        <span><strong><?= $minutasSalvas ?></strong><small>minutas salvas</small></span>
      </div>
      <button class="contract-model-button" type="button" data-open-contract="editar-modelo-contrato.php">Editar modelo-base <span aria-hidden="true">›</span></button>
    </section>

    <section class="contract-steps" aria-labelledby="contract-steps-title">
      <h2 id="contract-steps-title">Fluxo de assinatura</h2>
      <ol>
        <li><span>1</span><div><strong>Orçamento aprovado</strong><p>O painel aproveita cliente, valor, prazo e local da obra.</p></div></li>
        <li><span>2</span><div><strong>Minuta conferida</strong><p>Revise dados que ainda precisam de decisão humana.</p></div></li>
        <li><span>3</span><div><strong>Assinatura física</strong><p>Imprima a versão final para prestador e cliente assinarem.</p></div></li>
      </ol>
    </section>

    <section class="contract-approved" aria-labelledby="contract-approved-title">
      <div><p class="eyebrow">Disponíveis para minuta</p><h2 id="contract-approved-title">Orçamentos aprovados</h2></div><span><?= $totalAprovados ?></span>
      <?php if ($orcamentosAprovados): ?>
        <?php foreach ($orcamentosAprovados as $orcamento): $temMinuta = !empty($orcamento['minuta_id']); ?>
          <article class="contract-item <?= $temMinuta ? 'has-draft' : 'needs-draft' ?>">
            <div class="contract-item-main">
              <span class="contract-item-status"><?= $temMinuta ? 'Minuta salva' : 'Preparar minuta' ?></span>
              <strong><?= esc($orcamento['cliente']) ?></strong>
              <small><?= esc($orcamento['codigo']) ?> · <?= esc(moeda((int) $orcamento['total_centavos'])) ?></small>
            </div>
            <div class="contract-item-side">
              <small><?= $temMinuta ? 'Atualizada em ' . esc(dataCurta($orcamento['minuta_atualizada_em'])) : 'Aprovado em ' . esc(dataCurta($orcamento['atualizado_em'])) ?></small>
              <button type="button" data-open-contract="editar-contrato.php?id=<?= (int) $orcamento['id'] ?>"><?= $temMinuta ? 'Reabrir minuta' : 'Criar minuta' ?> <span aria-hidden="true">›</span></button>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="contract-empty-state">
          <strong>Nenhum contrato pendente.</strong>
          <p>Aprove um orçamento para liberar a criação da minuta.</p>
          <a class="secondary-action" href="orcamentos.php">Ver orçamentos</a>
        </div>
      <?php endif; ?>
    </section>
  </main>
  <?php $activeNav = 'contratos'; require __DIR__ . '/app/nav-painel.php'; ?>
  <dialog class="contract-preview-dialog" data-contract-preview aria-label="Visualizar minuta de contrato"><iframe title="Minuta de contrato" data-contract-frame></iframe></dialog>
  <script src="scripts/contratos.js"></script>
</body>
</html>

