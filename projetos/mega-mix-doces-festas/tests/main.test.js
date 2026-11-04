const test = require('node:test');
const assert = require('node:assert');
const {
  PRODUCTS, SHELVES, getStatus, localNow, searchProducts,
  buildListMessage, buildSearchMessage, whatsappUrl, parseSaved
} = require('../scripts/main.js');

test('dia útil dentro do horário mostra aberto', () => {
  assert.deepStrictEqual(getStatus(2, 10 * 60), { open: true, text: 'Aberto agora · fecha às 19h' });
});

test('sábado no intervalo do almoço mostra quando reabre', () => {
  assert.deepStrictEqual(getStatus(6, 12 * 60 + 30), { open: false, text: 'Fechado agora · abre às 14h' });
});

test('sábado à noite pula o domingo e aponta para segunda', () => {
  assert.strictEqual(getStatus(6, 20 * 60).text, 'Fechado agora · abre segunda às 9h');
});

test('domingo aponta para amanhã', () => {
  assert.strictEqual(getStatus(0, 15 * 60).text, 'Fechado agora · abre amanhã às 9h');
});

test('horário local usa fuso de São Paulo', () => {
  // 2026-11-03T13:00Z = terça, 10h em São Paulo (UTC-3)
  assert.deepStrictEqual(localNow(new Date('2026-11-03T13:00:00Z')), { day: 2, minutes: 600 });
});

test('todo produto pertence a uma vitrine existente e tem id único', () => {
  const shelves = new Set(SHELVES.map((s) => s.id));
  PRODUCTS.forEach((p) => assert.ok(shelves.has(p.shelf), p.id));
  assert.strictEqual(new Set(PRODUCTS.map((p) => p.id)).size, PRODUCTS.length);
});

test('busca ignora acento e maiúsculas', () => {
  assert.deepStrictEqual(searchProducts(PRODUCTS, 'BALÕES'), ['baloes']);
  assert.deepStrictEqual(searchProducts(PRODUCTS, 'lapis'), ['lapis']);
});

test('busca aceita plural e sinônimo', () => {
  assert.ok(searchProducts(PRODUCTS, 'pirulitos').includes('pirulitos'));
  assert.ok(searchProducts(PRODUCTS, 'bexiga').includes('baloes'));
});

test('busca vazia mostra tudo e busca sem resultado volta vazia', () => {
  assert.strictEqual(searchProducts(PRODUCTS, '  ').length, PRODUCTS.length);
  assert.deepStrictEqual(searchProducts(PRODUCTS, 'parafuso'), []);
});

test('mensagem da lista inclui itens e observação', () => {
  assert.strictEqual(buildListMessage(['Pirulitos', 'Copos descartáveis'], ' 50 copos '),
    'Olá, Megamix! Vim pelo site e gostaria de consultar esta lista:\n• Pirulitos\n• Copos descartáveis\n\n50 copos');
});

test('mensagem só com observação não deixa linha vazia', () => {
  assert.strictEqual(buildListMessage([], 'vela número 7'),
    'Olá, Megamix! Vim pelo site e gostaria de consultar esta lista:\nvela número 7');
});

test('lista vazia vira consulta simples', () => {
  assert.strictEqual(buildListMessage([], ''), 'Olá, Megamix! Vim pelo site e gostaria de fazer uma consulta.');
});

test('pergunta da busca leva o termo digitado', () => {
  assert.strictEqual(buildSearchMessage(' forminha nº 4 '), 'Olá, Megamix! Vim pelo site. Vocês têm forminha nº 4?');
});

test('link do WhatsApp usa número E.164 sem símbolos', () => {
  assert.match(whatsappUrl('oi'), /^https:\/\/wa\.me\/5551995481422\?text=oi$/);
});

test('lista salva ignora itens inválidos e dados corrompidos', () => {
  assert.deepStrictEqual(parseSaved('["balas","sumiu","copos"]', PRODUCTS), ['balas', 'copos']);
  assert.deepStrictEqual(parseSaved('{quebrado', PRODUCTS), []);
  assert.deepStrictEqual(parseSaved(null, PRODUCTS), []);
});
