const search = document.querySelector('[data-budget-search]');
const cards = [...document.querySelectorAll('[data-budget-card]')];
const filters = [...document.querySelectorAll('[data-filter]')];
const empty = document.querySelector('[data-empty-results]');
let activeFilter = 'todos';

function filterBudgets() {
  const query = search.value.trim().toLocaleLowerCase('pt-BR');
  let visible = 0;
  cards.forEach((card) => {
    const matchesFilter = activeFilter === 'todos' || card.dataset.status === activeFilter;
    const matchesQuery = !query || card.dataset.search.toLocaleLowerCase('pt-BR').includes(query);
    card.hidden = !matchesFilter || !matchesQuery;
    if (!card.hidden) visible += 1;
  });
  empty.hidden = visible > 0;
}

search?.addEventListener('input', filterBudgets);
filters.forEach((filter) => filter.addEventListener('click', () => {
  activeFilter = filter.dataset.filter;
  filters.forEach((item) => item.classList.toggle('is-active', item === filter));
  filterBudgets();
}));

