const test = require('node:test');
const assert = require('node:assert/strict');
const {
  CATEGORIES, getStatus, localNow, searchCategories,
  buildListMessage, buildSearchMessage, whatsappUrl, parseSaved
} = require('../scripts/main.js');

test('horário de sábado considera o intervalo e a próxima abertura', () => {
  assert.deepEqual(getStatus(6, 12 * 60 + 30), { open: false, text: 'Fechado agora · abre às 14h' });
  assert.equal(getStatus(6, 20 * 60).text, 'Fechado agora · abre segunda às 9h');
});

test('horário local usa o fuso de São Paulo', () => {
  assert.deepEqual(localNow(new Date('2026-11-03T13:00:00Z')), { day: 2, minutes: 600 });
});

test('categorias não se repetem e busca encontra termos cotidianos', () => {
  assert.equal(new Set(CATEGORIES.map((c) => c.id)).size, CATEGORIES.length);
  assert.deepEqual(searchCategories(CATEGORIES, 'BALÕES'), ['festas']);
  assert.deepEqual(searchCategories(CATEGORIES, 'copos'), ['descartaveis']);
  assert.deepEqual(searchCategories(CATEGORIES, 'lápis'), ['escolar']);
  assert.deepEqual(searchCategories(CATEGORIES, '  '), CATEGORIES.map((c) => c.id));
  assert.deepEqual(searchCategories(CATEGORIES, 'parafuso'), []);
});

test('consulta descreve itens sem fingir pedido fechado', () => {
  assert.equal(buildListMessage(['Balas e pirulitos', 'Descartáveis'], ' 50 copos '),
    'Olá, Megamix! Vim pelo site e gostaria de consultar estes itens:\n• Balas e pirulitos\n• Descartáveis\n\n50 copos');
  assert.equal(buildListMessage([], ''), 'Olá, Megamix! Vim pelo site e gostaria de fazer uma consulta.');
  assert.equal(buildSearchMessage(' balão número 7 '), 'Olá, Megamix! Vim pelo site. Vocês têm balão número 7?');
});

test('link de consulta usa E.164 e codifica a mensagem', () => {
  assert.equal(whatsappUrl('Olá!'), 'https://wa.me/5551995481422?text=Ol%C3%A1!');
});

test('lista salva ignora itens antigos, duplicados e dados inválidos', () => {
  assert.deepEqual(parseSaved('["balas","balas","sumiu","descartaveis"]', CATEGORIES), ['balas', 'descartaveis']);
  assert.deepEqual(parseSaved('{quebrado', CATEGORIES), []);
  assert.deepEqual(parseSaved(null, CATEGORIES), []);
});
