const menuToggle = document.querySelector(".menu-toggle");
const siteNav = document.querySelector(".site-nav");
const backToTopButton = document.querySelector("#back-to-top");
const revealElements = document.querySelectorAll(".reveal");
const messageButton = document.querySelector("#message-button");
const dailyMessageTitle = document.querySelector("#daily-message-title");
const dailyMessageText = document.querySelector("#daily-message-text");
const oracleButtons = document.querySelectorAll(".oracle-card");
const oracleResult = document.querySelector("#oracle-result");
const filterButtons = document.querySelectorAll(".filter-btn");
const serviceCards = document.querySelectorAll(".service-card");
const contactForm = document.querySelector("#contact-form");

// Controla a abertura e o fechamento do menu mobile.
if (menuToggle && siteNav) {
  menuToggle.addEventListener("click", () => {
    const isOpen = siteNav.classList.toggle("is-open");
    menuToggle.setAttribute("aria-expanded", String(isOpen));
  });

  siteNav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      siteNav.classList.remove("is-open");
      menuToggle.setAttribute("aria-expanded", "false");
    });
  });
}

const messages = [
  {
    title: "A energia de hoje pede escuta",
    text: "Respire antes de decidir. O silêncio revela detalhes que a pressa costuma esconder."
  },
  {
    title: "Seu foco merece proteção",
    text: "Escolha uma prioridade e cuide dela com constância. Nem toda porta precisa ser aberta ao mesmo tempo."
  },
  {
    title: "Movimento com intenção",
    text: "Pequenas ações repetidas com consciência criam mudanças mais fortes do que impulsos passageiros."
  }
];

// Atualiza a mensagem destacada na pagina inicial.
if (messageButton && dailyMessageTitle && dailyMessageText) {
  messageButton.addEventListener("click", () => {
    const nextMessage = messages[Math.floor(Math.random() * messages.length)];
    dailyMessageTitle.textContent = nextMessage.title;
    dailyMessageText.textContent = nextMessage.text;
  });
}

const oracleMessages = {
  lua: "Lua: observe sua intuição com carinho. Nem tudo precisa de resposta imediata para fazer sentido.",
  sol: "Sol: sua presença ganha força quando você assume o protagonismo com leveza e verdade.",
  estrela: "Estrela: mantenha a esperança ativa. Há caminhos se organizando mesmo quando ainda não estão visíveis."
};

// Exibe uma resposta diferente conforme o simbolo escolhido no oraculo.
oracleButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const symbol = button.dataset.oracle;
    if (oracleResult && symbol && oracleMessages[symbol]) {
      oracleResult.textContent = oracleMessages[symbol];
    }
  });
});

// Filtra os cards de servicos por categoria.
filterButtons.forEach((button) => {
  button.addEventListener("click", () => {
    filterButtons.forEach((item) => item.classList.remove("is-active"));
    button.classList.add("is-active");

    const selectedFilter = button.dataset.filter;
    serviceCards.forEach((card) => {
      const category = card.dataset.category;
      const shouldShow = selectedFilter === "todos" || category === selectedFilter;
      card.classList.toggle("is-hidden", !shouldShow);
    });
  });
});

// Mostra o botao de voltar ao topo apos certa rolagem.
function toggleBackToTop() {
  if (!backToTopButton) {
    return;
  }

  if (window.scrollY > 300) {
    backToTopButton.classList.add("visible");
  } else {
    backToTopButton.classList.remove("visible");
  }
}

window.addEventListener("scroll", toggleBackToTop);
toggleBackToTop();

if (backToTopButton) {
  backToTopButton.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

// Ativa animacoes de entrada quando os elementos aparecem na tela.
if ("IntersectionObserver" in window) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.16 });

  revealElements.forEach((element) => observer.observe(element));
} else {
  revealElements.forEach((element) => element.classList.add("visible"));
}

// Centraliza a validacao do formulario de contato no lado do cliente.
if (contactForm) {
  const formStatus = document.querySelector("#form-status");
  const nameField = contactForm.querySelector("#nome");
  const emailField = contactForm.querySelector("#email");
  const subjectField = contactForm.querySelector("#assunto");
  const messageField = contactForm.querySelector("#mensagem");

  function setFieldError(field, message) {
    const fieldWrapper = field.closest(".field");
    const errorMessage = fieldWrapper ? fieldWrapper.querySelector(".error-message") : null;

    if (fieldWrapper) {
      fieldWrapper.classList.add("error");
    }

    if (errorMessage) {
      errorMessage.textContent = message;
    }
  }

  function clearFieldError(field) {
    const fieldWrapper = field.closest(".field");
    const errorMessage = fieldWrapper ? fieldWrapper.querySelector(".error-message") : null;

    if (fieldWrapper) {
      fieldWrapper.classList.remove("error");
    }

    if (errorMessage) {
      errorMessage.textContent = "";
    }
  }

  function validateEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  // Remove o estado de erro conforme o usuario corrige os campos.
  [nameField, emailField, subjectField, messageField].forEach((field) => {
    field.addEventListener("input", () => clearFieldError(field));
    field.addEventListener("change", () => clearFieldError(field));
  });

  contactForm.addEventListener("submit", (event) => {
    event.preventDefault();

    let isValid = true;

    if (formStatus) {
      formStatus.textContent = "";
    }

    if (!nameField.value.trim() || nameField.value.trim().length < 3) {
      setFieldError(nameField, "Informe um nome com pelo menos 3 caracteres.");
      isValid = false;
    }

    if (!emailField.value.trim() || !validateEmail(emailField.value.trim())) {
      setFieldError(emailField, "Digite um e-mail válido.");
      isValid = false;
    }

    if (!subjectField.value) {
      setFieldError(subjectField, "Selecione um assunto.");
      isValid = false;
    }

    if (!messageField.value.trim() || messageField.value.trim().length < 10) {
      setFieldError(messageField, "Escreva uma mensagem com pelo menos 10 caracteres.");
      isValid = false;
    }

    if (!isValid) {
      return;
    }

    if (formStatus) {
      formStatus.textContent = "Sua mensagem foi preparada com sucesso. Em breve entraremos em contato pelo e-mail informado.";
    }

    contactForm.reset();
  });
}
