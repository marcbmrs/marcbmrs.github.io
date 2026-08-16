(() => {
  const form = document.querySelector("[data-briefing-form]");
  if (!form) return;

  const checkboxError = document.querySelector("[data-checkbox-error]");
  const status = document.querySelector("[data-form-status]");
  const whatsapp = "51980109047";

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
    const lines = ["Olá, Marcos! Preenchi o briefing para criação de site.", "", "*DADOS DE CONTATO*"];
    const sections = {
      "Nome": "Nome",
      "Empresa": "Empresa",
      "WhatsApp": "WhatsApp",
      "E-mail": "E-mail",
      "O que a empresa faz": "SOBRE O NEGÓCIO",
      "Região de atendimento": null,
      "Tempo de negócio": null,
      "Público que deseja atrair": null,
      "Diferenciais": null,
      "Objetivo principal do site": "OBJETIVO DO SITE",
      "Páginas e informações desejadas": null,
      "Links atuais": "PRESENÇA ATUAL E MATERIAIS",
      "Materiais disponíveis": null,
      "Referências": null,
      "Domínio": "MOMENTO DO PROJETO",
      "Prazo desejado": null,
      "Observações finais": null
    };

    for (const [name, heading] of Object.entries(sections)) {
      const value = String(answers.get(name) || "").trim();
      if (!value) continue;
      if (heading && name !== "Nome" && name !== "Empresa" && name !== "WhatsApp" && name !== "E-mail") lines.push("", `*${heading}*`);
      lines.push(`${name}: ${value}`);
    }
    lines.push("", "Necessidades: " + checkedNeeds.map((item) => item.value).join(", "));
    lines.push("", "Fico à disposição para complementar alguma informação.");

    const url = `https://wa.me/${whatsapp}?text=${encodeURIComponent(lines.join("\n"))}`;
    status.textContent = "Abrindo o WhatsApp para você revisar a mensagem…";
    window.open(url, "_blank", "noopener");
  });
})();
