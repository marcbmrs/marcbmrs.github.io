const money = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
const selectedItems = [];
const list = document.querySelector('[data-selected-list]');
const total = document.querySelector('[data-total]');
const emptyState = document.querySelector('[data-empty-state]');
const pdfButton = document.querySelector('[data-pdf-button]');
const addFeedback = document.querySelector('[data-add-feedback]');
const validationNotice = document.createElement('p');
validationNotice.className = 'form-error builder-validation';
validationNotice.hidden = true;
validationNotice.setAttribute('role', 'alert');
document.querySelector('.client-card')?.before(validationNotice);
const requiredFieldChecks = [
  ['[data-client-name]', 'Informe o nome do cliente.'],
  ['[data-client-phone]', 'Informe um WhatsApp válido com DDD.'],
  ['[data-work-city]', 'Informe a cidade da obra.'],
  ['[data-work-neighborhood]', 'Informe o bairro da obra.'],
];
function requisitosPendentes() {
  const pendentes = [];
  requiredFieldChecks.forEach(([selector, message]) => {
    const field = document.querySelector(selector);
    if (!field) return;
    const valid = selector === '[data-client-phone]' ? Boolean(telefoneBrasil(field.value)) : Boolean(field.value.trim());
    field.toggleAttribute('aria-invalid', !valid);
    if (!valid) pendentes.push({ field, message });
  });
  if (selectedItems.length === 0) pendentes.push({ field: document.querySelector('[data-catalog-search]'), message: 'Adicione pelo menos um item ao orçamento.' });
  return pendentes;
}
function limparValidacao() { validationNotice.hidden = true; document.querySelectorAll('[aria-invalid="true"]').forEach((field) => field.removeAttribute('aria-invalid')); }
function mostrarValidacao(pendentes = requisitosPendentes()) { const first = pendentes[0]; validationNotice.textContent = pendentes.length === 1 ? first.message : `${first.message} Mais ${pendentes.length - 1} pendência${pendentes.length > 2 ? 's' : ''}.`; validationNotice.hidden = false; first.field?.focus({ preventScroll: false }); validationNotice.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); }
function mostrarErroSistema(message) { validationNotice.textContent = message; validationNotice.hidden = false; document.querySelector('[data-review-dialog]')?.close(); validationNotice.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); }
function telefoneBrasil(value) { let numero = value.replace(/\D/g, ''); if (/^\d{10,11}$/.test(numero)) numero = `55${numero}`; return /^55\d{2}9\d{8}$/.test(numero) ? numero : null; }
function telefoneExibicao(numero) { const partes = numero.match(/^55(\d{2})(\d{4,5})(\d{4})$/); return partes ? `+55 (${partes[1]}) ${partes[2]}-${partes[3]}` : ''; }
function mascaraTelefone(value) { let numero = value.replace(/\D/g, ''); if (/^55\d{2}9\d{8}$/.test(numero)) numero = numero.slice(2); numero = numero.slice(0, 11); if (numero.length <= 2) return numero ? `(${numero}` : ''; if (numero.length <= 7) return `(${numero.slice(0, 2)}) ${numero.slice(2)}`; return `(${numero.slice(0, 2)}) ${numero.slice(2, 7)}-${numero.slice(7)}`; }
const campoTelefone = document.querySelector('[data-client-phone]');
campoTelefone?.addEventListener('input', (event) => { event.target.value = mascaraTelefone(event.target.value); });
if (campoTelefone) campoTelefone.value = mascaraTelefone(campoTelefone.value);

function periodLabel(item) { return item.rateType === 'diaria' ? 'Diárias' : item.rateType === 'mensal' ? 'Meses' : null; }
function subtotal(item) { return item.quantity * item.value * (item.period ?? 1); }
function desconto(item) { return Math.max(0, (item.tableValue ?? item.value) * item.quantity * (item.period ?? 1) - subtotal(item)); }

function dadosObrigatoriosPreenchidos() {
  return Boolean(
    document.querySelector('[data-client-name]').value.trim()
    && telefoneBrasil(document.querySelector('[data-client-phone]').value)
    && document.querySelector('[data-work-city]').value.trim()
    && document.querySelector('[data-work-neighborhood]').value.trim(),
  );
}

function atualizarAcaoPrincipal() {
  pdfButton.disabled = selectedItems.length === 0 || !dadosObrigatoriosPreenchidos();
}

function anunciarItemAdicionado(nome) {
  if (!addFeedback) return;
  addFeedback.replaceChildren(document.createTextNode(`${nome} foi adicionado.`));
  const link = document.createElement('a');
  link.href = '#resumo';
  link.textContent = 'Ver resumo';
  addFeedback.append(link);
  addFeedback.hidden = false;
  total.classList.remove('is-updated');
  void total.offsetWidth;
  total.classList.add('is-updated');
}

function render() {
  list.replaceChildren(); let totalValue = 0;
  const groups = { 'Mão de obra': [], 'Equipamentos locados': [] };
  selectedItems.forEach((item, index) => groups[item.category].push({ item, index }));
  Object.entries(groups).forEach(([title, items]) => {
    if (!items.length) return;
    const heading = document.createElement('p'); heading.className = 'summary-group'; heading.textContent = title; list.append(heading);
    items.forEach(({ item, index }) => {
      totalValue += subtotal(item); const period = periodLabel(item); const row = document.createElement('div'); row.className = 'selected-item';
      const economia = desconto(item); const referencia = item.tableValue ?? item.value;
      row.innerHTML = `<div class="item-name"><strong>${item.name}</strong><small>${item.category}${item.rateLabel ? ` · ${item.rateLabel}` : ` · por ${item.unit}`}</small></div><strong class="item-subtotal"><small>Subtotal</small>${money.format(subtotal(item) / 100)}</strong><div class="item-edit-grid"><label>Qtd.<input data-quantity="${index}" type="number" min="0.01" step="0.01" inputmode="decimal" value="${item.quantity}"></label>${period ? `<label>${period}<input data-period="${index}" type="number" min="1" step="1" inputmode="numeric" value="${item.period}"></label>` : `<span class="item-field-placeholder" aria-hidden="true"></span>`}<label class="item-price-field">Preço negociado<input data-value="${index}" type="number" min="0" step="0.01" inputmode="decimal" value="${(item.value / 100).toFixed(2)}"><small class="item-pricing">Tabela: ${money.format(referencia / 100)}${economia ? ` · Desconto: ${money.format(economia / 100)}` : ''}</small></label></div><button class="remove-item" type="button" data-remove="${index}" aria-label="Remover ${item.name}">×</button>`;
      list.append(row);
    });
  });
  total.textContent = money.format(totalValue / 100); emptyState.hidden = selectedItems.length > 0; atualizarAcaoPrincipal();
  document.querySelectorAll('[data-count]').forEach((counter) => { counter.textContent = groups[counter.dataset.count].length; });
  document.querySelectorAll('[data-cart-count]').forEach((counter) => { counter.textContent = selectedItems.length; });
}

document.querySelectorAll('[data-add-item]').forEach((button) => button.addEventListener('click', () => { const tableValue = Number(button.dataset.value); selectedItems.push({ name: button.dataset.name, category: 'Mão de obra', unit: button.dataset.unit, tableValue, value: tableValue, quantity: 1 }); render(); anunciarItemAdicionado(button.dataset.name); }));
document.querySelectorAll('[data-add-equipment]').forEach((button) => button.addEventListener('click', () => {
  const rateType = button.dataset.rateType; const existing = selectedItems.find((item) => item.name === button.dataset.name && item.rateType === rateType);
  if (existing) existing.quantity += 1;
  else { const tableValue = Number(button.dataset.value); selectedItems.push({ name: button.dataset.name, category: 'Equipamentos locados', rateType, rateLabel: button.dataset.rateLabel, tableValue, value: tableValue, quantity: 1, period: rateType === 'peca' ? null : 1 }); } render(); anunciarItemAdicionado(button.dataset.name);
}));
document.querySelectorAll('[data-client-name], [data-client-phone], [data-work-city], [data-work-neighborhood]').forEach((field) => field.addEventListener('input', () => { limparValidacao(); atualizarAcaoPrincipal(); }));
list.addEventListener('input', (event) => {
  const index = Number(event.target.dataset.quantity ?? event.target.dataset.period ?? event.target.dataset.value); if (!Number.isInteger(index) || !selectedItems[index]) return; const item = selectedItems[index];
  if (event.target.dataset.quantity !== undefined) item.quantity = Math.max(0.01, Number(event.target.value) || 0.01);
  if (event.target.dataset.period !== undefined) item.period = Math.max(1, Math.round(Number(event.target.value) || 1));
  if (event.target.dataset.value !== undefined) item.value = Math.max(0, Math.round((Number(event.target.value) || 0) * 100)); render();
});
list.addEventListener('click', (event) => { const index = Number(event.target.dataset.remove); if (Number.isInteger(index)) { selectedItems.splice(index, 1); render(); } });
document.querySelectorAll('[data-tab]').forEach((tab) => tab.addEventListener('click', () => { document.querySelectorAll('[data-tab]').forEach((item) => { item.classList.toggle('is-active', item === tab); item.setAttribute('aria-selected', String(item === tab)); }); document.querySelectorAll('[data-panel]').forEach((panel) => { panel.hidden = panel.dataset.panel !== tab.dataset.tab; }); }));
pdfButton.addEventListener('click', () => {
  const dialog = document.querySelector('[data-review-dialog]'); const content = document.querySelector('[data-review-content]'); content.replaceChildren(); let value = 0;
  selectedItems.forEach((item) => { value += subtotal(item); const line = document.createElement('div'); line.className = 'review-line'; line.innerHTML = `<span>${item.name}</span><strong>${money.format(subtotal(item) / 100)}</strong>`; content.append(line); });
  const totalLine = document.createElement('p'); totalLine.className = 'review-total'; totalLine.innerHTML = `Total estimado <strong>${money.format(value / 100)}</strong>`; content.append(totalLine); dialog.showModal();
});
document.querySelector('[data-close-review]')?.addEventListener('click', () => document.querySelector('[data-review-dialog]').close());
document.querySelector('[data-save-draft]')?.addEventListener('click', async (event) => {
  const pendentes = requisitosPendentes();
  if (pendentes.length) { mostrarValidacao(pendentes); atualizarAcaoPrincipal(); return; }
  const telefone = telefoneBrasil(document.querySelector('[data-client-phone]').value);
  const totalCentavos = selectedItems.reduce((sum, item) => sum + subtotal(item), 0);
  const prazo = Number(document.querySelector('[data-work-duration]').value);
  const payload = { cliente: { nome: document.querySelector('[data-client-name]').value, telefone, cidade: document.querySelector('[data-work-city]').value, bairro: document.querySelector('[data-work-neighborhood]').value, prazo_dias: Number.isInteger(prazo) && prazo > 0 ? prazo : null, endereco: document.querySelector('[data-work-address]').value, complemento: document.querySelector('[data-work-complement]').value, cep: document.querySelector('[data-work-zip]').value, validade: document.querySelector('[data-client-validity]').value }, total_centavos: totalCentavos, itens: selectedItems.map((item) => ({ categoria: item.category === 'Mão de obra' ? 'mao_de_obra' : 'equipamento', nome: item.name, unidade: item.unit ?? item.rateLabel, quantidade: item.quantity, periodo: item.period, periodo_tipo: item.rateType ?? null, valor_tabela_centavos: item.tableValue ?? item.value, valor_centavos: item.value, subtotal_centavos: subtotal(item) })) };
  event.target.disabled = true; event.target.textContent = 'Salvando…';
  try { const response = await fetch('salvar-orcamento.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const result = await response.json(); if (!result.ok) throw new Error(result.erro); window.location.href = 'orcamentos.php'; } catch (error) { event.target.disabled = false; event.target.textContent = 'Salvar rascunho'; mostrarErroSistema(error.message || 'Não foi possível salvar o orçamento.'); }
});



let activeServiceFilter = 'todos';
function aplicarFiltroCatalogo() {
  const query = document.querySelector('[data-catalog-search]')?.value.trim().toLocaleLowerCase('pt-BR') ?? '';
  document.querySelectorAll('[data-panel]:not([hidden]) .catalog-item').forEach((item) => {
    const matchesQuery = !query || item.textContent.toLocaleLowerCase('pt-BR').includes(query);
    const matchesGroup = !item.dataset.serviceGroup || activeServiceFilter === 'todos' || item.dataset.serviceGroup === activeServiceFilter;
    item.hidden = !matchesQuery || !matchesGroup;
  });
}
document.querySelectorAll('[data-service-filter]').forEach((filter) => filter.addEventListener('click', () => {
  activeServiceFilter = filter.dataset.serviceFilter;
  document.querySelectorAll('[data-service-filter]').forEach((item) => item.classList.toggle('is-active', item === filter));
  aplicarFiltroCatalogo();
}));
document.querySelector('[data-catalog-search]')?.addEventListener('input', aplicarFiltroCatalogo);
document.querySelectorAll('[data-tab]').forEach((tab) => tab.addEventListener('click', () => {
  activeServiceFilter = 'todos';
  document.querySelectorAll('[data-service-filter]').forEach((item) => item.classList.toggle('is-active', item.dataset.serviceFilter === 'todos'));
  aplicarFiltroCatalogo();
}));




