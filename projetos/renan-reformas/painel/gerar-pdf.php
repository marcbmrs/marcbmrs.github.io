<?php
declare(strict_types=1);

require __DIR__ . '/app/auth.php';
exigirEquipe();
require __DIR__ . '/app/db.php';

$id = (int) ($_GET['id'] ?? 0);
$consulta = banco()->prepare('SELECT o.*, c.nome, c.telefone FROM orcamentos o JOIN clientes c ON c.id = o.cliente_id WHERE o.id = ?');
$consulta->execute([$id]);
$orcamento = $consulta->fetch();
if (!$orcamento) { http_response_code(404); exit('Orçamento não encontrado.'); }
$consulta = banco()->prepare('SELECT * FROM itens_orcamento WHERE orcamento_id = ? ORDER BY categoria, id');
$consulta->execute([$id]);
$itens = $consulta->fetchAll();

function esc(string $texto): string { return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); }
function valor(int $centavos): string { return 'R$ ' . number_format($centavos / 100, 2, ',', '.'); }
function quantidadeComUnidade(float $quantidade, string $unidade): string
{
    $numero = rtrim(rtrim(number_format($quantidade, 2, ',', '.'), '0'), ',');
    return trim($numero . ' ' . $unidade);
}
function descontoDoItem(array $item): int
{
    $valorTabela = (int) ($item['valor_tabela_centavos'] ?? $item['valor_unitario_centavos']);
    $periodo = $item['periodo_quantidade'] === null ? 1 : (float) $item['periodo_quantidade'];
    $valorCheio = (int) round((float) $item['quantidade'] * $periodo * $valorTabela);
    return max(0, $valorCheio - (int) $item['subtotal_centavos']);
}
function telefoneFormatado(string $telefone): string { $numero = preg_replace('/\D+/', '', $telefone); return preg_match('/^55(\d{2})(\d{5})(\d{4})$/', $numero, $partes) ? '+55 (' . $partes[1] . ') ' . $partes[2] . '-' . $partes[3] : $telefone; }
function nomeArquivo(string $nome): string { $limpo = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nome) ?: 'cliente'; $limpo = preg_replace('/[^A-Za-z0-9]+/', '-', trim($limpo)) ?: 'cliente'; return 'Renan-Reformas-' . trim($limpo, '-') . '-' . date('d-m-Y') . '.pdf'; }
$arquivo = nomeArquivo($orcamento['nome']);
$tituloDocumento = pathinfo($arquivo, PATHINFO_FILENAME);
$numeroWhatsapp = preg_replace('/\D+/', '', $orcamento['telefone'] ?? '');
$mensagemWhatsapp = rawurlencode('Olá, ' . $orcamento['nome'] . '! Segue o orçamento ' . $orcamento['codigo'] . ' da Renan Reformas para sua avaliação.');
$local = array_filter([$orcamento['endereco'] ?? null, $orcamento['complemento'] ?? null, $orcamento['bairro'] ?? null, $orcamento['cidade'] ?? null, $orcamento['cep'] ?? null]);
$status = ['rascunho' => 'Rascunho', 'pronto_para_envio' => 'Proposta comercial', 'aguardando_cliente' => 'Enviado ao cliente', 'negociacao' => 'Em negociação', 'aprovado' => 'Aprovado', 'recusado' => 'Recusado', 'expirado' => 'Expirado', 'cancelado' => 'Cancelado'][$orcamento['status']] ?? 'Orçamento';
$prazoDias = (int) ($orcamento['prazo_dias'] ?? 0);
$totalOrcamento = (int) $orcamento['total_centavos'];
$entrada = (int) round($totalOrcamento * 0.30);
$parcelaVigesimoDia = $prazoDias > 20 ? (int) round($totalOrcamento * 0.10) : 0;
$saldoFinal = $totalOrcamento - $entrada - $parcelaVigesimoDia;
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= esc($tituloDocumento) ?></title><style>
@page { size:A4; margin:0; } * { box-sizing:border-box; } body { margin:0; color:#18202a; background:#e8e8e8; font-family:Arial, sans-serif; } .print-actions { display:flex; align-items:center; justify-content:center; gap:12px; padding:18px; font-size:13px; } .print-actions button,.print-actions a { min-height:42px; padding:0 15px; border:0; border-radius:8px; color:#fff; background:#151515; font-weight:700; text-decoration:none; cursor:pointer; } .print-actions a { color:#151515; border:1px solid #aaa; background:#fff; display:inline-flex; align-items:center; } .print-actions a.whatsapp-send { color:#fff; border-color:#168a63; background:#168a63; } .paper { width:210mm; min-height:297mm; margin:0 auto; padding:16mm 16mm 14mm; background:#fff; } .header { display:flex; justify-content:space-between; align-items:flex-start; padding-bottom:10mm; border-bottom:2px solid #c18400; } .logo { width:142px; height:auto; } .document-label { color:#6d706f; font-size:10px; font-weight:700; letter-spacing:1.5px; text-align:right; text-transform:uppercase; } .document-code { margin-top:5px; color:#111; font-size:14px; font-weight:800; } .hero { display:flex; justify-content:space-between; align-items:end; gap:16px; padding:10mm 0 8mm; } .eyebrow { margin:0 0 5px; color:#a96f00; font-size:10px; font-weight:800; letter-spacing:1.3px; text-transform:uppercase; } h1 { margin:0; color:#111; font-size:29px; letter-spacing:-.7px; } .status { margin:0; padding:7px 10px; border-radius:99px; color:#715000; background:#fff0c9; font-size:10px; font-weight:800; white-space:nowrap; } .client-grid { display:grid; grid-template-columns:1.1fr .9fr; gap:1px; border:1px solid #e5e0d7; border-radius:8px; overflow:hidden; background:#e5e0d7; } .client-grid > div { min-height:58px; padding:11px 13px; background:#fff; } .client-grid .wide { grid-column:1 / -1; } .label { display:block; margin-bottom:4px; color:#77736d; font-size:9px; font-weight:700; letter-spacing:.9px; text-transform:uppercase; } .client-grid strong { color:#171717; font-size:13px; } .section-title { display:flex; justify-content:space-between; align-items:baseline; margin:10mm 0 4mm; } h2 { margin:0; color:#1b1b1b; font-size:15px; } .section-title span { color:#77736d; font-size:10px; } table { width:100%; border-collapse:collapse; font-size:11px; } thead { color:#6e6a64; font-size:9px; letter-spacing:.7px; text-align:left; text-transform:uppercase; } th { padding:0 8px 7px; font-weight:800; } th:first-child, td:first-child { padding-left:0; } th:last-child, td:last-child { padding-right:0; text-align:right; } tbody tr { border-top:1px solid #e8e5df; } td { padding:10px 8px; vertical-align:top; } .item-name { color:#1a1a1a; font-weight:700; } .item-meta { display:block; margin-top:3px; color:#77736d; font-size:9px; } .number { text-align:right; white-space:nowrap; } .total { display:flex; justify-content:flex-end; margin-top:8mm; } .total-box { min-width:245px; padding:13px 15px; border-radius:8px; color:#fff; background:#151515; } .total-box small { display:block; margin-bottom:4px; color:#f4d78e; font-size:9px; font-weight:800; letter-spacing:.8px; text-transform:uppercase; } .total-box strong { display:block; font-size:22px; } .payment-panel { margin-top:9mm; padding:13px; border:1px solid #e5e0d7; border-radius:8px; background:#fff; } .payment-panel h2 { margin-bottom:10px; } .payment-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:7px; } .payment-grid > div { padding:9px; border-radius:6px; background:#faf8f3; } .payment-grid span { display:block; color:#77736d; font-size:8px; font-weight:700; line-height:1.35; text-transform:uppercase; } .payment-grid strong { display:block; margin-top:4px; color:#171717; font-size:12px; } .payment-methods,.climate-note { margin:10px 0 0; color:#55514b; font-size:10px; line-height:1.5; } .climate-note { padding-top:9px; border-top:1px solid #e7e3db; } .terms { margin-top:9mm; padding:12px 13px; border-left:3px solid #c18400; background:#faf8f3; color:#55514b; font-size:10px; line-height:1.55; } .terms strong { color:#222; } footer { display:flex; justify-content:space-between; margin-top:12mm; padding-top:5mm; border-top:1px solid #e7e3db; color:#77736d; font-size:9px; } @media print { body { background:#fff; } .print-actions { display:none; } .paper { width:auto; min-height:0; margin:0; } } @media screen and (max-width:760px) { .paper { width:100%; min-height:100vh; padding:28px 20px; } .client-grid,.payment-grid { grid-template-columns:1fr; } .client-grid .wide { grid-column:auto; } .hero { align-items:flex-start; flex-direction:column; } .total { justify-content:stretch; } .total-box { width:100%; } }
/* Tabela de serviços: leitura comparativa, com as colunas numéricas sempre alinhadas. */
.paper section table { border:1px solid #e3dfd6; border-collapse:separate; border-spacing:0; border-radius:8px; overflow:hidden; table-layout:fixed; }
.paper section table th:nth-child(1), .paper section table td:nth-child(1) { width:43%; }
.paper section table th:nth-child(2), .paper section table td:nth-child(2) { width:17%; }
.paper section table th:nth-child(3), .paper section table td:nth-child(3), .paper section table th:nth-child(4), .paper section table td:nth-child(4) { width:20%; text-align:right; }
.paper section table thead { background:#faf8f3; }
.paper section table th { padding:9px 10px; }
.paper section table td { padding:10px; }
.paper section table tbody tr { border-top:0; }
.paper section table tbody tr + tr td { border-top:1px solid #e8e5df; }
.item-discount { color:#17644e; font-weight:700; }
</style></head><body>
<div class="print-actions"><button type="button" onclick="window.print()">Salvar como PDF</button><?php if (preg_match('/^55\d{2}9\d{8}$/', $numeroWhatsapp)): ?><a class="whatsapp-send" href="https://wa.me/<?= $numeroWhatsapp ?>?text=<?= $mensagemWhatsapp ?>" target="_blank" rel="noopener">Enviar pelo WhatsApp <span aria-hidden="true">↗</span></a><?php endif; ?><a href="orcamento.php?id=<?= $id ?>">Voltar ao orçamento</a><span>Nome sugerido: <strong><?= esc($arquivo) ?></strong></span></div>
<article class="paper"><header class="header"><img class="logo" src="assets/images/logo-renan-reformas.svg" alt="Renan Reformas"><div><div class="document-label">Orçamento de serviços</div><div class="document-code"><?= esc($orcamento['codigo']) ?> · Revisão <?= (int) $orcamento['revisao'] ?></div></div></header>
<section class="hero"><div><p class="eyebrow">Proposta preparada para</p><h1><?= esc($orcamento['nome']) ?></h1></div><p class="status"><?= esc($status) ?></p></section>
<section class="client-grid"><div><span class="label">WhatsApp</span><strong><?= esc(telefoneFormatado($orcamento['telefone'] ?? 'Não informado')) ?></strong></div><div><span class="label">Validade da proposta</span><strong><?= esc($orcamento['validade'] ?: 'A confirmar') ?></strong></div><div><span class="label">Prazo estimado</span><strong><?= $prazoDias > 0 ? $prazoDias . ' ' . ($prazoDias === 1 ? 'dia' : 'dias') : 'A confirmar' ?></strong></div><div class="wide"><span class="label">Local da obra</span><strong><?= esc($local ? implode(' · ', $local) : ($orcamento['local_obra'] ?: 'A confirmar')) ?></strong></div></section>
<section><div class="section-title"><h2>Serviços e locações</h2><span><?= count($itens) ?> <?= count($itens) === 1 ? 'item' : 'itens' ?></span></div><table><thead><tr><th>Descrição</th><th>Qtd.</th><th>Valor negociado</th><th>Subtotal</th></tr></thead><tbody><?php foreach ($itens as $item): $periodo = $item['periodo_quantidade'] ? ' · ' . number_format((float) $item['periodo_quantidade'], 0, ',', '.') . ' ' . ($item['periodo_tipo'] === 'diaria' ? 'diária(s)' : ($item['periodo_tipo'] === 'mensal' ? 'mês(es)' : '')) : ''; $desconto = descontoDoItem($item); $valorTabela = (int) ($item['valor_tabela_centavos'] ?? $item['valor_unitario_centavos']); ?><tr><td><span class="item-name"><?= esc($item['descricao']) ?></span><span class="item-meta"><?= esc($item['categoria'] === 'mao_de_obra' ? 'Mão de obra' : 'Locação') ?> · Por <?= esc($item['unidade']) ?><?= esc($periodo) ?></span><?php if ($desconto > 0): ?><span class="item-meta item-discount">Preço de tabela: <?= valor($valorTabela) ?> · Desconto negociado: −<?= valor($desconto) ?></span><?php endif; ?></td><td class="number"><?= esc(quantidadeComUnidade((float) $item['quantidade'], (string) $item['unidade'])) ?></td><td class="number"><?= valor((int) $item['valor_unitario_centavos']) ?></td><td class="number"><strong><?= valor((int) $item['subtotal_centavos']) ?></strong></td></tr><?php endforeach; ?></tbody></table></section>
<div class="total"><div class="total-box"><small>Total estimado</small><strong><?= valor($totalOrcamento) ?></strong></div></div>
<section class="payment-panel"><h2>Prazo e condições de pagamento</h2><?php if ($prazoDias > 0): ?><p class="payment-methods"><strong>Prazo estimado:</strong> <?= $prazoDias ?> <?= $prazoDias === 1 ? 'dia' : 'dias' ?> para execução da obra.</p><?php endif; ?><div class="payment-grid"><div><span>Início da obra · 30%</span><strong><?= valor($entrada) ?></strong></div><?php if ($prazoDias > 20): ?><div><span>20º dia de obra · 10%</span><strong><?= valor($parcelaVigesimoDia) ?></strong></div><?php endif; ?><div><span>Conclusão da obra · <?= $prazoDias > 20 ? '60%' : '70%' ?></span><strong><?= valor($saldoFinal) ?></strong></div></div><p class="payment-methods"><strong>Formas de pagamento:</strong> Pix ou dinheiro. Pagamento no cartão sob consulta.</p><p class="climate-note"><strong>Sobre o prazo:</strong> o prazo estimado poderá ser ampliado por condições climáticas que impeçam ou prejudiquem a execução segura e adequada dos serviços.</p></section>
<aside class="terms"><strong>Observações importantes</strong><br>Esta proposta considera os serviços e valores descritos acima. Alterações de escopo, quantidade ou condições de execução podem exigir uma nova revisão do orçamento.</aside>
<footer><span>Renan Reformas · Orçamento <?= esc($orcamento['codigo']) ?></span><span>Emitido em <?= date('d/m/Y') ?></span></footer></article>
<script>window.addEventListener('load', () => setTimeout(() => window.print(), 350));</script></body></html>
