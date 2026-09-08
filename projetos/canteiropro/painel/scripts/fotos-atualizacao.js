(() => {
  const campo = document.querySelector('input[name="fotos[]"]');
  if (!campo) return;
  const aviso = document.createElement('small');
  const previews = document.createElement('div');
  const acoes = document.createElement('div');
  aviso.className = 'photo-selection';
  previews.className = 'photo-previews';
  acoes.className = 'photo-picker-actions';
  const selecionadas = [];
  aviso.textContent = 'Você pode selecionar até 5 fotos de uma vez.';
  campo.classList.add('photo-picker-input');
  const escolher = document.createElement('button');
  escolher.type = 'button';
  escolher.className = 'photo-picker-button';
  escolher.innerHTML = '<span aria-hidden="true">▣</span> Escolher fotos';
  escolher.addEventListener('click', () => { campo.removeAttribute('capture'); campo.click(); });
  const camera = document.createElement('button');
  camera.type = 'button';
  camera.className = 'photo-picker-button is-camera';
  camera.innerHTML = '<span aria-hidden="true">◉</span> Usar câmera';
  camera.addEventListener('click', () => { campo.setAttribute('capture', 'environment'); campo.click(); });
  acoes.append(escolher, camera);
  campo.insertAdjacentElement('afterend', acoes);
  acoes.insertAdjacentElement('afterend', aviso);
  aviso.insertAdjacentElement('afterend', previews);
  function sincronizarCampo() {
    const lista = new DataTransfer();
    selecionadas.forEach((arquivo) => lista.items.add(arquivo));
    campo.files = lista.files;
  }
  function renderizar() {
    const quantidade = selecionadas.length;
    aviso.textContent = !quantidade ? 'Você pode selecionar até 5 fotos de uma vez.' : quantidade > 5 ? 'Selecione no máximo 5 fotos para esta atualização.' : `${quantidade} ${quantidade === 1 ? 'foto selecionada' : 'fotos selecionadas'} para publicar.`;
    aviso.classList.toggle('is-error', quantidade > 5);
    previews.replaceChildren();
    selecionadas.forEach((arquivo) => {
      if (!arquivo.type.startsWith('image/')) return;
      const item = document.createElement('figure');
      const imagem = document.createElement('img');
      imagem.src = URL.createObjectURL(arquivo);
      imagem.alt = `Prévia de ${arquivo.name}`;
      const nome = document.createElement('figcaption');
      nome.textContent = arquivo.name;
      const remover = document.createElement('button');
      remover.type = 'button';
      remover.className = 'photo-preview-remove';
      remover.setAttribute('aria-label', `Remover ${arquivo.name}`);
      remover.textContent = '×';
      remover.addEventListener('click', () => {
        selecionadas.splice(selecionadas.indexOf(arquivo), 1);
        sincronizarCampo();
        renderizar();
      });
      item.append(imagem, nome, remover);
      previews.append(item);
    });
  }
  campo.addEventListener('change', () => {
    [...campo.files].forEach((arquivo) => {
      const repetida = selecionadas.some((item) => item.name === arquivo.name && item.size === arquivo.size && item.lastModified === arquivo.lastModified);
      if (!repetida && selecionadas.length < 5) selecionadas.push(arquivo);
    });
    sincronizarCampo();
    renderizar();
  });
})();

