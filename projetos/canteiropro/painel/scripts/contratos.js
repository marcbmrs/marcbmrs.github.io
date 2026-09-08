const contractDialog = document.querySelector('[data-contract-preview]');
const contractFrame = document.querySelector('[data-contract-frame]');

document.querySelectorAll('[data-open-contract]').forEach((button) => button.addEventListener('click', () => {
  if (!contractDialog || !contractFrame) return;
  contractFrame.src = button.dataset.openContract;
  contractDialog.showModal();
}));

window.addEventListener('message', (event) => {
  if (event.data === 'fechar-minuta') contractDialog?.close();
});

contractDialog?.addEventListener('click', (event) => {
  if (event.target === contractDialog) contractDialog.close();
});

