(() => {
  const resumo = document.querySelector('[data-summary-field]');
  const contador = document.querySelector('[data-summary-count]');
  const form = document.querySelector('[data-update-form]');
  const submit = document.querySelector('[data-update-submit]');

  if (resumo && contador) {
    const atualizarContador = () => {
      contador.textContent = String(resumo.value.length);
    };
    resumo.addEventListener('input', atualizarContador);
    atualizarContador();
  }

  form?.addEventListener('submit', () => {
    if (!submit) return;
    submit.disabled = true;
    submit.textContent = 'Publicando...';
  });
})();

