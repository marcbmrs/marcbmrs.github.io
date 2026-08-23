const toggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.site-nav');

if (toggle && navigation) {
  toggle.addEventListener('click', () => {
    const isOpen = navigation.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(isOpen));
  });

  navigation.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    navigation.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  }));
}

const year = document.querySelector('#year');
if (year) year.textContent = new Date().getFullYear();

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const revealElements = [...document.querySelectorAll('.reveal')];

const showAllContent = () => {
  revealElements.forEach((element) => element.classList.add('is-visible'));
};

if (reducedMotion.matches || !('IntersectionObserver' in window)) {
  showAllContent();
} else {
  revealElements.forEach((element, index) => {
    const siblings = [...element.parentElement.children].filter((child) => child.classList.contains('reveal'));
    const siblingIndex = Math.max(0, siblings.indexOf(element));
    element.style.setProperty('--reveal-delay', `${Math.min(siblingIndex * 80, 240)}ms`);
  });

  document.documentElement.classList.add('motion-ready');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    });
  }, {
    threshold: 0.14,
    rootMargin: '0px 0px -7% 0px'
  });

  revealElements.forEach((element) => observer.observe(element));

  reducedMotion.addEventListener('change', (event) => {
    if (!event.matches) return;
    observer.disconnect();
    document.documentElement.classList.remove('motion-ready');
    showAllContent();
  }, { once: true });
}
