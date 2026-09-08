(() => {
  const dialog = document.querySelector('[data-confirm-dialog]');
  if (!dialog) return;

  let pendingForm = null;
  const title = dialog.querySelector('[data-confirm-title]');
  const message = dialog.querySelector('[data-confirm-message]');

  document.addEventListener('submit', (event) => {
    const form = event.target.closest('[data-delete-confirm]');
    if (!form) return;
    event.preventDefault();
    pendingForm = form;
    title.textContent = form.dataset.confirmTitle || 'Excluir este item?';
    message.textContent = form.dataset.confirmMessage || 'Esta ação não pode ser desfeita.';
    dialog.showModal();
  });

  dialog.querySelector('[data-confirm-cancel]').addEventListener('click', () => {
    pendingForm = null;
    dialog.close();
  });

  dialog.querySelector('[data-confirm-accept]').addEventListener('click', () => {
    if (pendingForm) pendingForm.submit();
  });

  dialog.addEventListener('click', (event) => {
    if (event.target === dialog) {
      pendingForm = null;
      dialog.close();
    }
  });
})();
