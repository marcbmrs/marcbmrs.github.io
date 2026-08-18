(() => {
  const form = document.querySelector('[data-content-form]');
  if (!form) return;
  const whatsapp = '5551980109047';
  const objectivesError = document.querySelector('[data-objectives-error]');
  const status = document.querySelector('[data-form-status]');

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    objectivesError.textContent = '';
    status.textContent = '';
    const objectives = [...form.querySelectorAll('input[name="Objetivos"]:checked')];
    if (!form.checkValidity()) {
      form.reportValidity();
      status.textContent = 'Revise os campos obrigatórios antes de continuar.';
      return;
    }
    if (!objectives.length) {
      objectivesError.textContent = 'Escolha ao menos um objetivo para o conteúdo.';
      form.querySelector('input[name="Objetivos"]').focus();
      return;
    }
    const data = new FormData(form);
    const sections = [
      ['CONTATO', ['Nome', 'Negócio', 'WhatsApp', 'Instagram']],
      ['NEGÓCIO E OBJETIVO', ['Oferta', 'Público', 'Próximo passo']],
      ['ASSUNTOS E LINGUAGEM', ['Temas', 'Diferenciais', 'Provas', 'Tom de voz']],
      ['DIREÇÃO VISUAL', ['Paleta', 'Preferências visuais', 'Referências']],
      ['MATERIAIS E MOMENTO', ['Materiais', 'Prioridade inicial', 'Observações finais']]
    ];
    const lines = ['Olá, Marcos! Preenchi meu Plano de Conteúdo para Instagram.'];
    sections.forEach(([title, fields]) => {
      const filled = fields.map((field) => [field, String(data.get(field) || '').trim()]).filter(([, value]) => value);
      if (!filled.length) return;
      lines.push('', `*${title}*`);
      filled.forEach(([field, value]) => lines.push(`${field}: ${value}`));
    });
    lines.push('', `Objetivos do conteúdo: ${objectives.map((item) => item.value).join(', ')}`);
    lines.push('', 'Posso complementar com arquivos, fotos e referências na mesma conversa.');
    status.textContent = 'Abrindo o WhatsApp para você revisar a mensagem…';
    window.open(`https://wa.me/${whatsapp}?text=${encodeURIComponent(lines.join('\n'))}`, '_blank', 'noopener');
  });
})();
