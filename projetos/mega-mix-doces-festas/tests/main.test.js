const test = require('node:test');
const assert = require('node:assert');
const { getStatus, buildMessage, submitLabel, placeholderFor, whatsappUrl, localNow } = require('../scripts/main.js');

test('dia útil dentro do horário mostra aberto', () => {
  assert.deepStrictEqual(getStatus(2, 10 * 60), { open: true, text: 'Aberto agora · fecha às 19h' });
});

test('sábado no intervalo do almoço mostra quando reabre', () => {
  assert.deepStrictEqual(getStatus(6, 12 * 60 + 30), { open: false, text: 'Fechado agora · abre às 14h' });
});

test('sexta à noite aponta para sábado de manhã', () => {
  assert.strictEqual(getStatus(5, 19 * 60).text, 'Fechado agora · abre amanhã às 9h');
});

test('sábado à noite pula o domingo e aponta para segunda', () => {
  assert.strictEqual(getStatus(6, 20 * 60).text, 'Fechado agora · abre segunda às 9h');
});

test('domingo aponta para amanhã', () => {
  assert.strictEqual(getStatus(0, 15 * 60).text, 'Fechado agora · abre amanhã às 9h');
});

test('mensagem de festa inclui itens e detalhes', () => {
  const msg = buildMessage('festa', ['Balas e pirulitos', 'Descartáveis'], '  tema Frozen, 30 crianças  ');
  assert.strictEqual(msg,
    'Olá, Megamix! Vim pelo site. Gostaria de consultar estes itens para uma festa:\n' +
    '• Balas e pirulitos\n• Descartáveis\n\ntema Frozen, 30 crianças');
});

test('mensagem sem motivo usa abertura padrão', () => {
  assert.strictEqual(buildMessage(null, ['Salgadinhos'], ''),
    'Olá, Megamix! Vim pelo site e queria saber se vocês têm:\n• Salgadinhos');
});

test('só detalhes, sem itens, não deixa linha em branco solta', () => {
  assert.strictEqual(buildMessage('produto', [], 'pirulito de coração'),
    'Olá, Megamix! Vim pelo site e queria saber se vocês têm:\npirulito de coração');
});

test('mensagem vazia vira consulta simples', () => {
  assert.strictEqual(buildMessage('revenda', [], '   '), 'Olá, Megamix! Vim pelo site e gostaria de fazer uma consulta.');
});

test('rótulo do botão acompanha a quantidade marcada', () => {
  assert.strictEqual(submitLabel(0), 'Consultar no WhatsApp');
  assert.strictEqual(submitLabel(1), 'Consultar 1 tipo de item no WhatsApp');
  assert.strictEqual(submitLabel(3), 'Consultar 3 tipos de item no WhatsApp');
});

test('exemplo do campo muda conforme o motivo', () => {
  assert.match(placeholderFor('producao'), /brigadeiros/);
  assert.match(placeholderFor(null), /balas sortidas/);
});

test('link do WhatsApp usa número E.164 sem símbolos', () => {
  assert.match(whatsappUrl('oi'), /^https:\/\/wa\.me\/5551995481422\?text=oi$/);
});

test('horário local usa fuso de São Paulo', () => {
  // 2026-11-03T13:00Z = terça, 10h em São Paulo (UTC-3)
  assert.deepStrictEqual(localNow(new Date('2026-11-03T13:00:00Z')), { day: 2, minutes: 600 });
});
