const money = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
const draft = window.orcamentoEmEdicao;
const selectedItems = draft.itens.map((item) => ({ ...item }));
const list = document.querySelector('[data-selected-list]');
const total = document.querySelector('[data-total]');
const emptyState = document.querySelector('[data-empty-state]');
const saveButton = document.querySelector('[data-save-changes]');
const addFeedback = document.createElement('p');
addFeedback.className = 'add-feedback';
addFeedback.hidden = true;
addFeedback.setAttribute('role', 'status');
document.querySelector('.catalog .tabs')?.before(addFeedback);
const telefoneBrasil = (value) => { let numero = value.replace(/\D/g, ''); if (/^\d{10,11}$/.test(numero)) numero = `55${numero}`; return /^55\d{2}9\d{8}$/.test(numero) ? numero : null; };
const telefoneExibicao = (numero) => { const partes = numero.match(/^55(\d{2})(\d{4,5})(\d{4})$/); return partes ? `+55 (${partes[1]}) ${partes[2]}-${partes[3]}` : ''; };
const mascaraTelefone = (value) => { let numero = value.replace(/\D/g, ''); if (/^55\d{2}9\d{8}$/.test(numero)) numero = numero.slice(2); numero = numero.slice(0, 11); if (numero.length <= 2) return numero ? `(${numero}` : ''; if (numero.length <= 7) return `(${numero.slice(0, 2)}) ${numero.slice(2)}`; return `(${numero.slice(0, 2)}) ${numero.slice(2, 7)}-${numero.slice(7)}`; };
const campoTelefone = document.querySelector('[data-client-phone]');
campoTelefone.addEventListener('input', (event) => { event.target.value = mascaraTelefone(event.target.value); });
campoTelefone.value = mascaraTelefone(campoTelefone.value);
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
  ['[data-work-duration]', 'Informe o prazo estimado em dias.'],
];
function requisitosPendentes() {
  const pendentes = [];
  requiredFieldChecks.forEach(([selector, message]) => {
    const field = document.querySelector(selector);
    if (!field) return;
    const valid = selector === '[data-client-phone]' ? Boolean(telefoneBrasil(field.value)) : selector === '[data-work-duration]' ? Number.isInteger(Number(field.value)) && Number(field.value) > 0 : Boolean(field.value.trim());
    field.toggleAttribute('aria-invalid', !valid);
    if (!valid) pendentes.push({ field, message });
  });
  if (selectedItems.length === 0) pendentes.push({ field: document.querySelector('[data-catalog-search]'), message: 'Mantenha pelo menos um item no orçamento.' });
  return pendentes;
}
function limparValidacao() { validationNotice.hidden = true; document.querySelectorAll('[aria-invalid="true"]').forEach((field) => field.removeAttribute('aria-invalid')); }
function mostrarValidacao(pendentes = requisitosPendentes()) { const first = pendentes[0]; validationNotice.textContent = pendentes.length === 1 ? first.message : `${first.message} Mais ${pendentes.length - 1} pendência${pendentes.length > 2 ? 's' : ''}.`; validationNotice.hidden = false; first.field?.focus({ preventScroll: false }); validationNotice.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); }
const periodLabel = (item) => item.rateType === 'diaria' ? 'Diárias' : item.rateType === 'mensal' ? 'Meses' : null;
const subtotal = (item) => Math.round(item.quantity * item.value * (item.period ?? 1));
const desconto = (item) => Math.max(0, (item.tableValue ?? item.value) * item.quantity * (item.period ?? 1) - subtotal(item));
const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[c]);
function dadosObrigatoriosPreenchidos() {
  return Boolean(
    document.querySelector('[data-client-name]').value.trim()
    && telefoneBrasil(document.querySelector('[data-client-phone]').value)
    && document.querySelector('[data-work-city]').value.trim()
    && document.querySelector('[data-work-neighborhood]').value.trim()
    && Number.isInteger(Number(document.querySelector('[data-work-duration]').value))
    && Number(document.querySelector('[data-work-duration]').value) > 0,
  );
}
function atualizarAcaoPrincipal() { const pendentes = requisitosPendentes(); saveButton.disabled = false; saveButton.classList.toggle('is-waiting', pendentes.length > 0); saveButton.textContent = pendentes.length ? 'Ver o que falta' : 'Salvar alterações'; }
function anunciarItemAdicionado(nome) {
  addFeedback.replaceChildren(document.createTextNode(`${nome} foi adicionado.`));
  const link = document.createElement('a'); link.href = '#resumo'; link.textContent = 'Ver resumo'; addFeedback.append(link); addFeedback.hidden = false;
  total.classList.remove('is-updated'); void total.offsetWidth; total.classList.add('is-updated');
}
function agruparCamposOpcionais() {
  const labels = ['[data-work-address]', '[data-work-complement]', '[data-work-zip]', '[data-client-validity]'].map((seletor) => document.querySelector(seletor)?.closest('label')).filter(Boolean);
  if (!labels.length) return;
  const details = document.createElement('details'); details.className = 'optional-details';
  details.open = labels.some((label) => label.querySelector('input').value.trim());
  const summary = document.createElement('summary'); summary.innerHTML = 'Adicionar endereço completo e validade <span>(opcional)</span>';
  const fields = document.createElement('div'); fields.className = 'fields optional-fields';
  labels[0].parentNode.insertBefore(details, labels[0]); details.append(summary, fields); labels.forEach((label) => fields.append(label));
}
function render() {
  list.replaceChildren(); let totalValue = 0; const groups = { 'Mão de obra': [], 'Equipamentos locados': [] };
  selectedItems.forEach((item, index) => groups[item.category].push({ item, index }));
  Object.entries(groups).forEach(([title, items]) => { if (!items.length) return; const heading = document.createElement('p'); heading.className = 'summary-group'; heading.textContent = title; list.append(heading); items.forEach(({ item, index }) => {
    totalValue += subtotal(item); const period = periodLabel(item); const row = document.createElement('div'); row.className = 'selected-item';
    const economia = desconto(item); const referencia = item.tableValue ?? item.value;
    row.innerHTML = `<div class="item-name"><strong>${escapeHtml(item.name)}</strong><small>${escapeHtml(item.category)}${item.rateLabel ? ` · ${escapeHtml(item.rateLabel)}` : ` · por ${escapeHtml(item.unit)}`}</small></div><strong class="item-subtotal"><small>Subtotal</small>${money.format(subtotal(item) / 100)}</strong><div class="item-edit-grid"><label>Qtd.<input data-quantity="${index}" type="number" min="0.01" step="0.01" inputmode="decimal" value="${item.quantity}"></label>${period ? `<label>${period}<input data-period="${index}" type="number" min="1" step="1" inputmode="numeric" value="${item.period}"></label>` : `<span class="item-field-placeholder" aria-hidden="true"></span>`}<label class="item-price-field">Preço negociado<input data-value="${index}" type="number" min="0" step="0.01" inputmode="decimal" value="${(item.value / 100).toFixed(2)}"><small class="item-pricing">Tabela: ${money.format(referencia / 100)}${economia ? ` · Desconto: ${money.format(economia / 100)}` : ''}</small></label></div><button class="remove-item" type="button" data-remove="${index}" aria-label="Remover ${escapeHtml(item.name)}">×</button>`; list.append(row);
  }); });
  total.textContent = money.format(totalValue / 100); emptyState.hidden = selectedItems.length > 0; atualizarAcaoPrincipal(); document.querySelectorAll('[data-count]').forEach((counter) => { counter.textContent = groups[counter.dataset.count].length; }); document.querySelectorAll('[data-cart-count]').forEach((counter) => { counter.textContent = selectedItems.length; });
}
document.querySelectorAll('[data-add-item]').forEach((button) => button.addEventListener('click', () => { const tableValue = Number(button.dataset.value); selectedItems.push({ name: button.dataset.name, category: 'Mão de obra', unit: button.dataset.unit, tableValue, value: tableValue, quantity: 1, period: null, rateType: null, rateLabel: null }); render(); anunciarItemAdicionado(button.dataset.name); }));
document.querySelectorAll('[data-add-equipment]').forEach((button) => button.addEventListener('click', () => { const rateType = button.dataset.rateType; const tableValue = Number(button.dataset.value); selectedItems.push({ name: button.dataset.name, category: 'Equipamentos locados', unit: button.dataset.rateLabel, rateType, rateLabel: button.dataset.rateLabel, tableValue, value: tableValue, quantity: 1, period: rateType === 'peca' ? null : 1 }); render(); anunciarItemAdicionado(button.dataset.name); }));
document.querySelectorAll('[data-client-name], [data-client-phone], [data-work-city], [data-work-neighborhood], [data-work-duration]').forEach((field) => field.addEventListener('input', () => { limparValidacao(); atualizarAcaoPrincipal(); }));
list.addEventListener('input', (event) => { const index = Number(event.target.dataset.quantity ?? event.target.dataset.period ?? event.target.dataset.value); if (!Number.isInteger(index) || !selectedItems[index]) return; const item = selectedItems[index]; if (event.target.dataset.quantity !== undefined) item.quantity = Math.max(0.01, Number(event.target.value) || 0.01); if (event.target.dataset.period !== undefined) item.period = Math.max(1, Math.round(Number(event.target.value) || 1)); if (event.target.dataset.value !== undefined) item.value = Math.max(0, Math.round((Number(event.target.value) || 0) * 100)); render(); });
list.addEventListener('click', (event) => { const index = Number(event.target.dataset.remove); if (Number.isInteger(index)) { selectedItems.splice(index, 1); render(); } });
document.querySelectorAll('[data-tab]').forEach((tab) => tab.addEventListener('click', () => { document.querySelectorAll('[data-tab]').forEach((item) => { item.classList.toggle('is-active', item === tab); item.setAttribute('aria-selected', String(item === tab)); }); document.querySelectorAll('[data-panel]').forEach((panel) => { panel.hidden = panel.dataset.panel !== tab.dataset.tab; }); const search = document.querySelector('[data-catalog-search]'); search.value = ''; document.querySelectorAll('.catalog-item').forEach((item) => { item.hidden = false; }); }));
document.querySelector('[data-catalog-search]').addEventListener('input', (event) => { const query = event.target.value.trim().toLocaleLowerCase('pt-BR'); document.querySelectorAll('[data-panel]:not([hidden]) .catalog-item').forEach((item) => { item.hidden = Boolean(query) && !item.textContent.toLocaleLowerCase('pt-BR').includes(query); }); });
saveButton.addEventListener('click', async () => { const pendentes = requisitosPendentes(); if (pendentes.length) { mostrarValidacao(pendentes); atualizarAcaoPrincipal(); return; } limparValidacao(); const telefone = telefoneBrasil(document.querySelector('[data-client-phone]').value); const cliente = { nome: document.querySelector('[data-client-name]').value.trim(), telefone, cidade: document.querySelector('[data-work-city]').value.trim(), bairro: document.querySelector('[data-work-neighborhood]').value.trim(), prazo_dias: Number(document.querySelector('[data-work-duration]').value), endereco: document.querySelector('[data-work-address]').value.trim(), complemento: document.querySelector('[data-work-complement]').value.trim(), cep: document.querySelector('[data-work-zip]').value.trim(), validade: document.querySelector('[data-client-validity]').value.trim() }; const payload = { id: draft.id, cliente, itens: selectedItems.map((item) => ({ id: item.id, categoria: item.category === 'Mão de obra' ? 'mao_de_obra' : 'equipamento', nome: item.name, unidade: item.unit ?? item.rateLabel, quantidade: item.quantity, periodo: item.period, periodo_tipo: item.rateType ?? null, valor_tabela_centavos: item.tableValue ?? item.value, valor_centavos: item.value })) }; saveButton.disabled = true; saveButton.textContent = 'Salvando…'; try { const response = await fetch('salvar-edicao.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const result = await response.json(); if (!result.ok) throw new Error(result.erro); window.location.href = result.redirect; } catch (error) { atualizarAcaoPrincipal(); saveButton.textContent = 'Salvar alterações'; mostrarErroSistema(error.message || 'Não foi possível salvar as alterações.'); } });
agruparCamposOpcionais();
render();



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

