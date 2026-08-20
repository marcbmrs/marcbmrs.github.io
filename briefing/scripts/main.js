(() => {
  const form = document.querySelector("[data-briefing-form]");
  if (!form) return;

  const checkboxError = document.querySelector("[data-checkbox-error]");
  const status = document.querySelector("[data-form-status]");
  const whatsapp = "51980109047";
  const regulatedDetails = document.querySelector("[data-regulated-details]");
  const regulatedChoices = form.querySelectorAll('input[name="Profissão regulamentada"]');

  function updateRegulatedFields() {
    const selected = form.querySelector('input[name="Profissão regulamentada"]:checked');
    const enabled = selected && selected.value === "Sim";
    regulatedDetails.hidden = !enabled;
    regulatedDetails.querySelectorAll("input, select, textarea").forEach((field) => { field.disabled = !enabled; });
  }

  regulatedChoices.forEach((choice) => choice.addEventListener("change", updateRegulatedFields));
  updateRegulatedFields();

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    checkboxError.textContent = "";
    status.textContent = "";

    const checkedNeeds = [...form.querySelectorAll('input[name="Necessidades"]:checked')];
    if (!form.checkValidity()) {
      form.reportValidity();
      status.textContent = "Revise os campos obrigatórios antes de continuar.";
      return;
    }
    if (!checkedNeeds.length) {
      checkboxError.textContent = "Escolha ao menos uma opção.";
      form.querySelector('input[name="Necessidades"]').focus();
      return;
    }

    const answers = new FormData(form);
    const lines = ["Olá, Marcos! Preenchi o briefing para criação de site."];
    const sections = [
      ["DADOS DE CONTATO", ["Nome", "Empresa", "WhatsApp", "E-mail"]],
      ["SOBRE O NEGÓCIO", ["O que a empresa faz", "Região de atendimento", "Tempo de negócio", "Público que deseja atrair", "Diferenciais"]],
      ["OBJETIVO DO SITE", ["Objetivo principal do site", "Páginas e informações desejadas"]],
      ["ATENDIMENTO", ["Modelo de atendimento", "Telefone público", "E-mail público", "Horários de atendimento", "Atendimento com hora marcada", "Endereço público"]],
      ["INFORMAÇÕES PROFISSIONAIS", ["Profissão regulamentada", "Profissão ou área", "Nome profissional público", "Registro profissional", "Atuação profissional", "Atendimento online", "Serviços não divulgados", "Observações profissionais"]],
      ["PRESENÇA ATUAL E MATERIAIS", ["Links atuais", "Materiais disponíveis", "Referências"]],
      ["MOMENTO DO PROJETO", ["Domínio", "Prazo desejado", "Responsável pelas aprovações", "Observações finais"]]
    ];

    sections.forEach(([heading, fields]) => {
      const filled = fields.map((name) => [name, String(answers.get(name) || "").trim()]).filter(([, value]) => value);
      if (!filled.length) return;
      lines.push("", `*${heading}*`);
      filled.forEach(([name, value]) => lines.push(`${name}: ${value}`));
    });
    lines.push("", "Necessidades: " + checkedNeeds.map((item) => item.value).join(", "));
    lines.push("", "Fico à disposição para complementar alguma informação.");

    const url = `https://wa.me/${whatsapp}?text=${encodeURIComponent(lines.join("\n"))}`;
    status.textContent = "Abrindo o WhatsApp para você revisar a mensagem…";
    window.open(url, "_blank", "noopener");
  });
})();
