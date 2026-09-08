document.querySelectorAll('.copy-link').forEach((button) => button.addEventListener('click', async () => {
  const original = button.textContent;
  try {
    await navigator.clipboard.writeText(new URL(button.dataset.link, location.href));
    button.textContent = 'Copiado';
    button.classList.add('is-success');
  } catch (error) {
    button.textContent = 'Não copiou';
  }
  setTimeout(() => { button.textContent = original; button.classList.remove('is-success'); }, 1800);
}));

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.querySelectorAll('details.work-card').forEach((card) => {
  const summary = card.querySelector('.work-card-summary');
  const content = card.querySelector('.work-card-content');
  if (!summary || !content) return;

  summary.addEventListener('click', (event) => {
    event.preventDefault();
    if (reducedMotion) { card.open = !card.open; return; }

    if (card.open) {
      content.style.maxHeight = `${content.scrollHeight}px`;
      requestAnimationFrame(() => { content.style.maxHeight = '0px'; content.classList.add('is-collapsing'); });
      content.addEventListener('transitionend', () => {
        card.open = false;
        content.style.maxHeight = '';
        content.classList.remove('is-collapsing');
      }, { once: true });
      return;
    }

    card.open = true;
    content.style.maxHeight = '0px';
    requestAnimationFrame(() => { content.style.maxHeight = `${content.scrollHeight}px`; });
    content.addEventListener('transitionend', () => { content.style.maxHeight = ''; }, { once: true });
  });
});

const createWorkDialog = document.querySelector('[data-create-work-dialog]');
let pendingWorkForm = null;

if (createWorkDialog) {
  document.querySelectorAll('[data-create-work]').forEach((form) => form.addEventListener('submit', (event) => {
    event.preventDefault();
    pendingWorkForm = form;
    createWorkDialog.showModal();
  }));

  createWorkDialog.querySelector('[data-create-work-cancel]').addEventListener('click', () => {
    pendingWorkForm = null;
    createWorkDialog.close();
  });

  createWorkDialog.querySelector('[data-create-work-confirm]').addEventListener('click', () => {
    if (pendingWorkForm) pendingWorkForm.submit();
  });

  createWorkDialog.addEventListener('click', (event) => {
    if (event.target === createWorkDialog) {
      pendingWorkForm = null;
      createWorkDialog.close();
    }
  });
}

