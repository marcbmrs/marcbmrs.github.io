(() => {
  const form = document.querySelector('[data-local-form]');
  if (!form) return;
  const whatsapp = '5551980109047';
  const modelError = document.querySelector('[data-model-error]');
  const status = document.querySelector('[data-form-status]');
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    modelError.textContent = '';
    status.textContent = '';
    if (!form.checkValidity()) { form.reportValidity(); status.textContent = 'Revise os campos obrigatórios antes de continuar.'; return; }
    const model = form.querySelector('input[name="Modelo de atendimento"]:checked');
    if (!model) { modelError.textContent = 'Escolha como a empresa atende os clientes.'; form.querySelector('input[name="Modelo de atendimento"]').focus(); return; }
    const data = new FormData(form);
    const sections = [
      ['CONTATO', ['Nome', 'Empresa', 'WhatsApp', 'E-mail']],
      ['ATENDIMENTO E REGIÃO', ['Modelo de atendimento', 'Endereço', 'Área de atendimento']],
      ['INFORMAÇÕES DA EMPRESA', ['Atividade principal', 'Categoria sugerida', 'Telefone público', 'Serviços', 'Horários', 'Links públicos']],
      ['SITUAÇÃO E MATERIAIS', ['Situação atual', 'Conta Google', 'Materiais disponíveis', 'Observações']]
    ];
    const lines = ['Olá, Marcos! Preenchi as informações iniciais para Presença Local no Google.'];
    sections.forEach(([title, fields]) => {
      const filled = fields.map((field) => [field, String(data.get(field) || '').trim()]).filter(([, value]) => value);
      if (!filled.length) return;
      lines.push('', `*${title}*`);
      filled.forEach(([field, value]) => lines.push(`${field}: ${value}`));
    });
    lines.push('', 'Entendo que não devo enviar senha, código de verificação ou dados de recuperação.');
    status.textContent = 'Abrindo o WhatsApp para você revisar a mensagem…';
    window.open(`https://wa.me/${whatsapp}?text=${encodeURIComponent(lines.join('\n'))}`, '_blank', 'noopener');
  });
})();
