<?php

declare(strict_types=1);

require __DIR__ . '/app/auth.php';
exigirEquipe();

if (isset($_GET['editar'])) { header('Location: editar.php?id=' . (int) $_GET['editar']); exit; }

$catalogo = require __DIR__ . '/app/catalogo-inicial.php';

function esc(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function valorInicial(array $item): int
{
    return (int) ($item['valor_centavos'] ?? $item['valor_minimo_centavos'] ?? 0);
}

function grupoServico(string $nome): string
{
    $texto = mb_strtolower($nome, 'UTF-8');
    if (str_contains($texto, 'solda')) return 'solda';
    if (str_contains($texto, 'piso') || str_contains($texto, 'porcelanato') || str_contains($texto, 'azulejo') || str_contains($texto, 'revestimento') || str_contains($texto, 'rejuntamento') || str_contains($texto, 'rodapé') || str_contains($texto, 'soleira') || str_contains($texto, 'peitoril')) return 'pisos';
    if (str_contains($texto, 'chapisco') || str_contains($texto, 'emboço') || str_contains($texto, 'reboco')) return 'reboco';
    if (str_contains($texto, 'demolição') || str_contains($texto, 'retirada') || str_contains($texto, 'abertura')) return 'demolicao';
    if (str_contains($texto, 'porta') || str_contains($texto, 'marco') || str_contains($texto, 'churrasqueira') || str_contains($texto, 'forno') || str_contains($texto, 'bancada') || str_contains($texto, 'nicho') || str_contains($texto, 'rasgos') || str_contains($texto, 'reparos') || str_contains($texto, 'telhado') || str_contains($texto, 'oitão') || str_contains($texto, 'impermeabilização')) return 'acabamento';
    return 'base';
}
function valorExibicao(array $item): string
{
    if (isset($item['valor_minimo_centavos'], $item['valor_maximo_centavos'])) {
        return 'R$ ' . number_format($item['valor_minimo_centavos'] / 100, 2, ',', '.')
            . '–' . number_format($item['valor_maximo_centavos'] / 100, 2, ',', '.');
    }

    return 'R$ ' . number_format(valorInicial($item) / 100, 2, ',', '.');
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#151515">
  <title>Novo orçamento | CanteiroPro</title>
  <link rel="stylesheet" href="styles/main.css">
</head>
<body class="quote-builder-page">
  <main class="app-shell quote-builder-shell">
    <header class="topbar">
      <a class="brand" href="painel.php" aria-label="Ir para o início do painel"><span class="brand-mark" aria-hidden="true"></span><span>CANTEIRO <b>PRO</b></span></a>
      <a class="quote-back" href="orcamentos.php"><span aria-hidden="true">‹</span> Orçamentos</a>
    </header>

    <section class="page-heading">
      <p class="eyebrow">Novo orçamento</p>
      <h1>Monte o orçamento da obra</h1>
      <p>Informe o cliente, adicione os itens e revise os valores antes de gerar o PDF.</p>
    </section>

    <section class="client-card" aria-labelledby="cliente-title">
      <div class="section-title"><span class="step">1</span><div><h2 id="cliente-title">Cliente e obra</h2><p>Informações para identificar o orçamento.</p></div></div>
      <div class="fields">
        <label>Nome do cliente <em>Obrigatório</em><input type="text" data-client-name placeholder="Ex.: João da Silva" autocomplete="name" required><small>Informe o nome de quem solicitou o orçamento.</small></label>
        <label>WhatsApp do cliente <em>Obrigatório</em><input type="tel" data-client-phone placeholder="Ex.: (51) 99999-9999" autocomplete="tel" inputmode="tel" required><small>Informe um celular com DDD.</small></label>
        <label>Cidade da obra <em>Obrigatório</em><input type="text" data-work-city placeholder="Ex.: Porto Alegre" autocomplete="address-level2" required><small>Informe a cidade onde o serviço será realizado.</small></label>
        <label>Bairro da obra <em>Obrigatório</em><input type="text" data-work-neighborhood placeholder="Ex.: Tristeza" autocomplete="address-level3" required><small>Ajuda a estimar deslocamento e visita técnica.</small></label>
      </div>
      <details class="optional-details">
        <summary>Adicionar prazo, endereço completo e validade <span>(opcional)</span></summary>
        <div class="fields optional-fields">
          <label>Prazo estimado da obra <em class="is-optional">Opcional</em><input type="number" data-work-duration placeholder="Ex.: 15" min="1" step="1" inputmode="numeric"><small>Use quando já houver uma previsão de dias para concluir a obra.</small></label>
          <label>Endereço e número <em class="is-optional">Opcional</em><input type="text" data-work-address placeholder="Ex.: Rua Exemplo, 123" autocomplete="street-address"><small>Preencha se o cliente já informou o local exato.</small></label>
          <label>Complemento ou referência <em class="is-optional">Opcional</em><input type="text" data-work-complement placeholder="Ex.: Casa dos fundos, perto da praça"><small>Inclua bloco, apartamento ou um ponto de referência.</small></label>
          <label>CEP <em class="is-optional">Opcional</em><input type="text" data-work-zip placeholder="Ex.: 91900-000" inputmode="numeric" autocomplete="postal-code"><small>Use apenas se ele já estiver disponível.</small></label>
          <label>Validade <em class="is-optional">Opcional</em><input type="text" data-client-validity placeholder="Ex.: 7 dias"><small>Defina por quanto tempo esta proposta vale.</small></label>
        </div>
      </details>
    </section>

    <section class="catalog" aria-labelledby="itens-title">
      <div class="section-title"><span class="step">2</span><div><h2 id="itens-title">Adicionar itens</h2><p>Busque pelo serviço ou filtre por etapa da obra.</p></div><a class="quote-cart" href="#resumo" aria-label="Ir para o resumo do orçamento"><span data-cart-count>0</span> itens</a></div>
      <p class="add-feedback" data-add-feedback role="status" hidden></p>
      <div class="tabs" role="tablist" aria-label="Tipo de item">
        <button type="button" class="tab is-active" role="tab" aria-selected="true" data-tab="servicos">Serviços <span data-count="Mão de obra">0</span></button>
        <button type="button" class="tab" role="tab" aria-selected="false" data-tab="equipamentos">Locação <span data-count="Equipamentos locados">0</span></button>
      </div>
      <div class="service-filters" role="group" aria-label="Filtrar serviços por etapa">
        <button class="service-filter is-active" type="button" data-service-filter="todos">Todos</button>
        <button class="service-filter" type="button" data-service-filter="base">Base</button>
        <button class="service-filter" type="button" data-service-filter="reboco">Reboco</button>
        <button class="service-filter" type="button" data-service-filter="pisos">Pisos</button>
        <button class="service-filter" type="button" data-service-filter="demolicao">Demolição</button>
        <button class="service-filter" type="button" data-service-filter="acabamento">Acabamento</button>
        <button class="service-filter" type="button" data-service-filter="solda">Solda</button>
      </div>
      <label class="catalog-search"><span class="sr-only">Buscar no catálogo</span><input type="search" placeholder="Buscar serviço ou equipamento" data-catalog-search autocomplete="off"></label>

      <div class="catalog-list" data-panel="servicos" role="tabpanel">
        <?php foreach ($catalogo['servicos_mao_de_obra'] as $item): ?>
          <button class="catalog-item" type="button" data-add-item data-category="Mão de obra" data-name="<?= esc($item['nome']) ?>" data-unit="<?= esc($item['unidade']) ?>" data-service-group="<?= esc(grupoServico($item['nome'])) ?>" data-value="<?= valorInicial($item) ?>">
            <span><strong><?= esc($item['nome']) ?></strong><small>Por <?= esc($item['unidade']) ?></small></span><b><?= valorExibicao($item) ?></b><i aria-hidden="true">+</i>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="catalog-list" data-panel="equipamentos" role="tabpanel" hidden>
        <p class="catalog-help">Selecione o equipamento. A locação usa diária, mensal ou peça conforme a tarifa cadastrada.</p>
        <?php foreach ($catalogo['equipamentos'] as $item): ?>
          <?php $tarifa = $item['tarifas'][0]; ?>
          <button class="catalog-item" type="button" data-add-equipment data-name="<?= esc($item['nome']) ?>" data-rate-type="<?= esc($tarifa['tipo']) ?>" data-rate-label="<?= esc($tarifa['rotulo']) ?>" data-value="<?= (int) $tarifa['valor_centavos'] ?>">
            <span><strong><?= esc($item['nome']) ?></strong><small><?= esc($tarifa['rotulo']) ?></small></span><b>R$ <?= number_format($tarifa['valor_centavos'] / 100, 2, ',', '.') ?></b><i aria-hidden="true">+</i>
          </button>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="selected" id="resumo" aria-labelledby="resumo-title">
      <div class="section-title"><span class="step">3</span><div><h2 id="resumo-title">Resumo do orçamento</h2><p data-empty-state>Nenhum item selecionado ainda.</p></div></div>
      <div class="selected-list" data-selected-list></div>
    </section>
  </main>

  <footer class="total-bar" aria-live="polite">
    <div><small>Total estimado</small><strong data-total>R$ 0,00</strong></div>
    <button type="button" class="primary-action" disabled data-pdf-button>Revisar orçamento</button>
  </footer>
  <dialog class="review-dialog" data-review-dialog aria-labelledby="review-title">
    <button class="dialog-close" type="button" data-close-review aria-label="Fechar revisão">×</button>
    <p class="eyebrow">Revisão</p><h2 id="review-title">Confira antes de gerar o PDF</h2>
    <div data-review-content></div>
    <button class="primary-action" type="button" data-save-draft>Salvar rascunho</button>
  </dialog>
  <script src="scripts/main.js"></script>
</body>
</html>




