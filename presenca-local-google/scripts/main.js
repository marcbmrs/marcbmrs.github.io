(() => {
  const form = document.querySelector('[data-local-form]');
  if (!form) return;
  const whatsapp = '5551980109047';
  const modelError = document.querySelector('[data-model-error]');
  const status = document.querySelector('[data-form-status]');
  const regulatedDetails = document.querySelector('[data-regulated-details]');
  const regulatedChoices = form.querySelectorAll('input[name="Profissão regulamentada"]');
  function formatAddress(data) {
    const street = String(data.get('Endereço - Rua') || '').trim();
    const number = String(data.get('Endereço - Número') || '').trim();
    const complement = String(data.get('Endereço - Complemento') || '').trim();
    const neighborhood = String(data.get('Endereço - Bairro') || '').trim();
    const city = String(data.get('Endereço - Cidade') || '').trim();
    const state = String(data.get('Endereço - UF') || '').trim();
    const zip = String(data.get('Endereço - CEP') || '').trim();
    const firstLine = [street, number].filter(Boolean).join(', ');
    return [firstLine, complement, neighborhood, [city, state].filter(Boolean).join(' - '), zip ? `CEP ${zip}` : ''].filter(Boolean).join(' | ');
  }

  function updateRegulatedFields() {
    const selected = form.querySelector('input[name="Profissão regulamentada"]:checked');
    const enabled = selected && selected.value === 'Sim';
    regulatedDetails.hidden = !enabled;
    regulatedDetails.querySelectorAll('input, select, textarea').forEach((field) => { field.disabled = !enabled; });
  }

  regulatedChoices.forEach((choice) => choice.addEventListener('change', updateRegulatedFields));
  updateRegulatedFields();

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
      ['INFORMAÇÕES DA EMPRESA', ['Atividade principal', 'Categoria sugerida', 'Telefone público', 'Serviços', 'Horários - Segunda a sexta', 'Horários - Sábado', 'Horários - Domingos e feriados', 'Atendimento com hora marcada', 'Exceções de horário', 'Links públicos']],
      ['INFORMAÇÕES PROFISSIONAIS', ['Profissão regulamentada', 'Profissão ou área', 'Nome profissional público', 'Registro profissional', 'Atuação profissional', 'Atendimento online', 'Serviços não divulgados', 'Observações profissionais']],
      ['SITUAÇÃO E MATERIAIS', ['Situação atual', 'Link do perfil no Google', 'Responsável pelo perfil', 'Conta Google', 'Materiais disponíveis', 'Observações']]
    ];
    const lines = ['Olá, Marcos! Preenchi as informações iniciais para Presença Local no Google.'];
    sections.forEach(([title, fields]) => {
      const filled = fields.map((field) => [field, field === 'Endereço' ? formatAddress(data) : String(data.get(field) || '').trim()]).filter(([, value]) => value);
      if (!filled.length) return;
      lines.push('', `*${title}*`);
      filled.forEach(([field, value]) => lines.push(`${field}: ${value}`));
    });
    lines.push('', 'Entendo que não devo enviar senha, código de verificação ou dados de recuperação.');
    status.textContent = 'Abrindo o WhatsApp para você revisar a mensagem…';
    window.open(`https://wa.me/${whatsapp}?text=${encodeURIComponent(lines.join('\n'))}`, '_blank', 'noopener');
  });
})();
