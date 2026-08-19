document.documentElement.classList.add('js');

const menuButton = document.querySelector('[data-menu-button]');
const menu = document.querySelector('[data-menu]');

menuButton?.addEventListener('click', () => {
  const open = menu.classList.toggle('open');
  menuButton.setAttribute('aria-expanded', String(open));
});

menu?.querySelectorAll('a').forEach((link) => {
  link.addEventListener('click', () => {
    menu.classList.remove('open');
    menuButton?.setAttribute('aria-expanded', 'false');
  });
});

document.getElementById('ano').textContent = new Date().getFullYear();

document.getElementById('form-orcamento')?.addEventListener('submit', (event) => {
  event.preventDefault();
  const data = new FormData(event.currentTarget);
  const message = [
    'Olá! Gostaria de solicitar um orçamento com a Renan Reformas.',
    '',
    `Nome: ${data.get('nome')}`,
    `Cidade da obra: ${data.get('cidade')}`,
    `Serviço: ${data.get('servico')}`,
    `Tipo de imóvel: ${data.get('imovel')}`
  ].join('\n');

  window.open(`https://wa.me/5551991740718?text=${encodeURIComponent(message)}`, '_blank', 'noopener');
});

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!reducedMotion) {
  document.documentElement.classList.add('motion-ready');

  requestAnimationFrame(() => {
    document.documentElement.classList.add('motion-start');
  });

  if ('IntersectionObserver' in window) {
    const revealItems = document.querySelectorAll([
      '.section-heading',
      '.service-card',
      '.project-card',
      '.about-media',
      '.about-copy',
      '.regions-grid > *',
      '.quote-grid > *'
    ].join(','));

    revealItems.forEach((item, index) => {
      item.classList.add('reveal');
      item.style.setProperty('--reveal-delay', `${(index % 4) * 70}ms`);
    });

    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -32px' });

    revealItems.forEach((item) => revealObserver.observe(item));
  }
}
