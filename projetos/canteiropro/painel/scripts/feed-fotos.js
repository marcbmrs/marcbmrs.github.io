(() => {
  const modal = document.querySelector('[data-photo-lightbox]');
  const imagem = modal?.querySelector('[data-lightbox-image]');
  if (!modal || !imagem) return;
  document.querySelectorAll('[data-photo-src]').forEach((botao) => botao.addEventListener('click', () => {
    imagem.src = botao.dataset.photoSrc;
    imagem.alt = botao.dataset.photoAlt || 'Foto da atualização';
    modal.showModal();
  }));
  modal.querySelector('.photo-lightbox-close').addEventListener('click', () => modal.close());
  modal.addEventListener('click', (evento) => { if (evento.target === modal) modal.close(); });
})();

