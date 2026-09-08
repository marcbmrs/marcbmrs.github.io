<?php
declare(strict_types=1);
require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';
$id = (int) ($_GET['id'] ?? 0);
$consulta = banco()->prepare('SELECT o.*, c.nome, c.telefone FROM orcamentos o JOIN clientes c ON c.id = o.cliente_id WHERE o.id = ?');
$consulta->execute([$id]); $orcamento = $consulta->fetch();
if (!$orcamento) { http_response_code(404); exit('Orçamento não encontrado.'); }
$consulta = banco()->prepare('SELECT * FROM itens_orcamento WHERE orcamento_id = ?'); $consulta->execute([$id]); $itens = $consulta->fetchAll();
function esc(string $texto): string { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
function dataCurta(?string $data): string { return $data ? date('d/m/Y', strtotime($data)) : 'Sem data'; }
function whatsappCliente(?string $telefone): ?string
{
    $numero = preg_replace('/\D+/', '', $telefone ?? '');
    if (preg_match('/^\d{10,11}$/', $numero)) $numero = '55' . $numero;
    return preg_match('/^55\d{2}9\d{8}$/', $numero) ? $numero : null;
}
$whatsapp = whatsappCliente($orcamento['telefone'] ?? null);
$minutaContrato = null;
$obraVinculada = null;
if (($orcamento['status'] ?? '') === 'aprovado') {
    $consulta = banco()->prepare('SELECT id, atualizado_em FROM minutas_contrato WHERE orcamento_id = ? LIMIT 1');
    $consulta->execute([$id]);
    $minutaContrato = $consulta->fetch() ?: null;
    $consulta = banco()->prepare('SELECT id, status, proxima_etapa, atualizado_em FROM obras_acompanhamento WHERE orcamento_id = ? LIMIT 1');
    $consulta->execute([$id]);
    $obraVinculada = $consulta->fetch() ?: null;
}
$status = [
    'rascunho' => ['Rascunho', 'Ainda em elaboração.', 'Complete os dados e deixe a proposta pronta.'],
    'pronto_para_envio' => ['Pronto para enviar', 'Revise uma última vez e marque quando for enviado ao cliente.', 'Gere o PDF, envie ao cliente e registre o envio.'],
    'aguardando_cliente' => ['Aguardando cliente', 'O orçamento foi enviado e está aguardando uma resposta.', 'Fale com o cliente ou avance para negociação/aprovação.'],
    'negociacao' => ['Em negociação', 'O cliente pediu ajuste ou está avaliando condições.', 'Crie uma revisão para preservar a proposta original.'],
    'aprovado' => ['Aprovado', 'Cliente aceitou este orçamento. A próxima etapa será o contrato.', 'Formalize, crie a obra e mantenha o cliente informado.'],
    'recusado' => ['Recusado', 'Cliente não aceitou este orçamento.', 'Crie nova revisão somente se o atendimento for retomado.'],
    'expirado' => ['Expirado', 'A validade passou sem uma aprovação registrada.', 'Crie uma nova revisão se ainda houver interesse.'],
    'cancelado' => ['Cancelado', 'Atendimento encerrado internamente.', 'Crie uma nova revisão se o cliente voltar.'],
][$orcamento['status']] ?? ['Status desconhecido', '', 'Abra os detalhes antes de avançar.'];
$etapasFluxo = [
    'rascunho' => ['Rascunho', 'is-current'],
    'pronto_para_envio' => ['Pronto', 'is-current'],
    'aguardando_cliente' => ['Enviado', 'is-current'],
    'negociacao' => ['Negociação', 'is-current'],
    'aprovado' => ['Aprovado', 'is-current'],
];
$statusAtual = (string) $orcamento['status'];
$ordemEtapas = array_keys($etapasFluxo);
$indiceAtual = array_search($statusAtual, $ordemEtapas, true);
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#151515"><title>Orçamento <?= esc($orcamento['codigo']) ?> | CanteiroPro</title><link rel="stylesheet" href="styles/main.css"></head>
<body class="budget-detail-page"><main class="app-shell budget-detail-shell"><header class="topbar"><a class="brand" href="painel.php" aria-label="Ir para o início do painel"><span class="brand-mark"></span><span>CANTEIRO <b>PRO</b></span></a><a class="quote-back" href="orcamentos.php"><span aria-hidden="true">‹</span> Orçamentos</a></header>
  <section class="budget-identity"><div class="budget-identity-meta"><span>Revisão <?= (int) $orcamento['revisao'] ?></span><span class="budget-current-status"><?= esc($status[0]) ?></span></div><h1><?= esc($orcamento['nome']) ?></h1><p><span><?= esc($orcamento['local_obra'] ?? 'Local não informado') ?></span><span aria-hidden="true">·</span><span><?= esc($orcamento['codigo']) ?></span><?php if ((int) ($orcamento['prazo_dias'] ?? 0) > 0): ?><span aria-hidden="true">·</span><span><?= (int) $orcamento['prazo_dias'] ?> <?= (int) $orcamento['prazo_dias'] === 1 ? 'dia' : 'dias' ?></span><?php endif; ?></p></section>
  <?php if ($whatsapp): ?><a class="client-whatsapp" href="https://wa.me/<?= $whatsapp ?>" target="_blank" rel="noopener"><span><small>Contato do cliente</small><strong><?= esc($orcamento['telefone']) ?></strong></span><span>WhatsApp <b aria-hidden="true">↗</b></span></a><?php elseif (!empty($orcamento['telefone'])): ?><p class="phone-warning">O telefone informado não tem um formato válido para abrir no WhatsApp.</p><?php endif; ?>
  <section class="selected budget-status-card"><div class="section-title"><span class="step">1</span><div><p class="status-kicker">Próxima etapa</p><h2><?= esc($status[2]) ?></h2><p><?= esc($status[1]) ?></p></div></div>
  <ol class="budget-flow" aria-label="Fluxo do orçamento">
    <?php foreach ($etapasFluxo as $chave => [$rotuloEtapa]): $indiceEtapa = array_search($chave, $ordemEtapas, true); $classeEtapa = $indiceAtual === false ? '' : ($indiceEtapa < $indiceAtual ? 'is-done' : ($indiceEtapa === $indiceAtual ? 'is-current' : '')); ?>
      <li class="<?= esc($classeEtapa) ?>"><span></span><small><?= esc($rotuloEtapa) ?></small></li>
    <?php endforeach; ?>
  </ol>
  <?php if ($orcamento['status'] === 'aprovado'): ?>
    <div class="handoff-card" aria-label="Continuidade depois da aprovação">
      <div class="handoff-heading">
        <div><p class="status-kicker">Continuidade</p><h3><?= $obraVinculada ? 'Obra já criada' : ($minutaContrato ? 'Contrato pronto para virar obra' : 'Prepare o contrato antes da obra') ?></h3></div>
        <span><?= $obraVinculada ? 'Obra' : 'Contrato' ?></span>
      </div>
      <ol class="handoff-flow">
        <li class="is-done"><span></span><small>Orçamento</small></li>
        <li class="<?= $minutaContrato ? 'is-done' : 'is-current' ?>"><span></span><small>Contrato</small></li>
        <li class="<?= $obraVinculada ? 'is-done' : ($minutaContrato ? 'is-current' : '') ?>"><span></span><small>Obra</small></li>
        <li class="<?= $obraVinculada ? 'is-current' : '' ?>"><span></span><small>Cliente</small></li>
      </ol>
      <p><?= $obraVinculada ? 'A obra foi aberta. O próximo passo é registrar ou revisar atualizações para o cliente.' : ($minutaContrato ? 'A minuta já foi salva. Agora você pode criar a obra e registrar a primeira atualização.' : 'Gere a minuta para revisar dados, condições e assinatura antes de abrir a execução.') ?></p>
      <div class="handoff-actions">
        <?php if (!$minutaContrato): ?>
          <a class="primary-action" href="editar-contrato.php?id=<?= $id ?>">Preparar contrato</a>
          <a class="secondary-action" href="contratos.php">Ver contratos</a>
        <?php elseif (!$obraVinculada): ?>
          <form method="post" action="criar-obra.php"><input type="hidden" name="orcamento_id" value="<?= $id ?>"><button class="primary-action">Criar obra</button></form>
          <a class="secondary-action" href="editar-contrato.php?id=<?= $id ?>">Reabrir contrato</a>
        <?php else: ?>
          <a class="primary-action" href="atualizar-obra.php?id=<?= (int) $obraVinculada['id'] ?>">Atualizar obra</a>
          <a class="secondary-action" href="obras.php">Ver obras</a>
        <?php endif; ?>
      </div>
      <small><?= $minutaContrato ? 'Minuta salva em ' . esc(dataCurta($minutaContrato['atualizado_em'])) : 'Minuta ainda não salva' ?><?= $obraVinculada ? ' · Obra atualizada em ' . esc(dataCurta($obraVinculada['atualizado_em'])) : '' ?></small>
    </div>
  <?php endif; ?>
  <div class="status-actions">
    <?php if ($orcamento['status'] === 'rascunho'): ?><a class="secondary-action" href="editar.php?id=<?= $id ?>">Continuar edição</a><form method="post" action="atualizar-status.php"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="status" value="pronto_para_envio"><button class="primary-action">Marcar como pronto</button></form>
    <?php elseif ($orcamento['status'] === 'pronto_para_envio'): ?><a class="secondary-action" href="editar.php?id=<?= $id ?>">Editar orçamento</a><form method="post" action="atualizar-status.php"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="status" value="aguardando_cliente"><button class="primary-action">Marcar como enviado</button></form>
    <?php elseif ($orcamento['status'] === 'aguardando_cliente' || $orcamento['status'] === 'negociacao'): ?><a class="secondary-action" href="criar-revisao.php?id=<?= $id ?>">Criar revisão</a><form method="post" action="atualizar-status.php"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="status" value="<?= $orcamento['status'] === 'aguardando_cliente' ? 'negociacao' : 'aguardando_cliente' ?>"><button class="secondary-action"><?= $orcamento['status'] === 'aguardando_cliente' ? 'Iniciar negociação' : 'Voltar a aguardar' ?></button></form><form method="post" action="atualizar-status.php"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="status" value="aprovado"><button class="primary-action">Marcar como aprovado</button></form>
    <?php elseif ($orcamento['status'] === 'aprovado'): ?><a class="secondary-action" href="criar-revisao.php?id=<?= $id ?>">Criar nova revisão</a><?php elseif (in_array($orcamento['status'], ['recusado', 'expirado', 'cancelado'], true)): ?><a class="secondary-action" href="criar-revisao.php?id=<?= $id ?>">Criar nova revisão</a><?php endif; ?>
  <?php if (in_array($orcamento['status'], ['rascunho', 'pronto_para_envio', 'aguardando_cliente', 'negociacao'], true)): ?><details class="status-more-actions"><summary>Mais ações</summary><div>
    <?php if (in_array($orcamento['status'], ['aguardando_cliente', 'negociacao'], true)): ?><form method="post" action="atualizar-status.php"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="status" value="recusado"><button class="text-action">Marcar como recusado</button></form><?php endif; ?>
    <?php if ($orcamento['status'] === 'aguardando_cliente'): ?><form method="post" action="atualizar-status.php"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="status" value="expirado"><button class="text-action">Marcar como expirado</button></form><?php endif; ?>
    <form method="post" action="atualizar-status.php"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="status" value="cancelado"><button class="text-action is-danger">Cancelar orçamento</button></form>
  </div></details><?php endif; ?>
  <?php if (in_array($orcamento['status'], ['pronto_para_envio', 'aguardando_cliente', 'negociacao', 'aprovado'], true)): ?><a class="pdf-action" href="gerar-pdf.php?id=<?= $id ?>" target="_blank" rel="noopener">Gerar PDF do orçamento <span aria-hidden="true">↗</span></a><?php endif; ?>
  </div></section>
  <section class="selected budget-items-card"><div class="items-heading"><div><p class="eyebrow">Composição</p><h2>Itens do orçamento</h2></div><span><?= count($itens) ?> <?= count($itens) === 1 ? 'item' : 'itens' ?></span></div><?php foreach ($itens as $item): ?><div class="review-line"><span><?= esc($item['descricao']) ?></span><strong>R$ <?= number_format($item['subtotal_centavos'] / 100, 2, ',', '.') ?></strong></div><?php endforeach; ?><p class="review-total">Total <strong>R$ <?= number_format($orcamento['total_centavos'] / 100, 2, ',', '.') ?></strong></p></section>
</main></body></html>






