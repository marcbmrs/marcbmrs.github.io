const menuToggle = document.querySelector(".menu-toggle");
const mobileMenu = document.querySelector("#mobile-menu");
const mobileLinks = document.querySelectorAll("#mobile-menu a");
const revealElements = document.querySelectorAll(".reveal");
const partnersTrack = document.querySelector("[data-partners-track]");
const serviceCardSummaries = document.querySelectorAll(".service-card-expandable .service-card-summary");
const serviceModal = document.querySelector("#service-modal");
const serviceModalTitle = document.querySelector("#service-modal-title");
const serviceModalContent = document.querySelector("#service-modal-content");
const serviceModalCloseTargets = document.querySelectorAll("[data-service-modal-close]");

const partnerLogos = [
  "ccr.png",
  "CPFL.png",
  "ceee-equatorial.png",
  "edisa.png",
  "fedex.png",
  "Forza.png",
  "kinto.png",
  "leaseplan.png",
  "lm.png",
  "maximmotors.jpg",
  "ouro_e_prata.png",
  "RGE.png",
  "savauto.png",
  "supermix.png",
  "triumph.jpg",
  "unesul.png",
  "unidas.png",
];

if ("scrollRestoration" in history) {
  history.scrollRestoration = "manual";
}

window.addEventListener("load", () => {
  window.scrollTo(0, 0);
});

if (menuToggle && mobileMenu) {
  menuToggle.addEventListener("click", () => {
    const expanded = menuToggle.getAttribute("aria-expanded") === "true";
    menuToggle.setAttribute("aria-expanded", String(!expanded));

    if (expanded) {
      mobileMenu.classList.remove("is-open");
      window.setTimeout(() => {
        mobileMenu.hidden = true;
      }, 220);
    } else {
      mobileMenu.hidden = false;
      window.requestAnimationFrame(() => {
        mobileMenu.classList.add("is-open");
      });
    }
  });

  mobileLinks.forEach((link) => {
    link.addEventListener("click", () => {
      menuToggle.setAttribute("aria-expanded", "false");
      mobileMenu.classList.remove("is-open");
      window.setTimeout(() => {
        mobileMenu.hidden = true;
      }, 220);
    });
  });
}

const closeServiceModal = () => {
  if (!serviceModal) {
    return;
  }

  serviceModal.hidden = true;
  document.body.style.overflow = "";
};

if (serviceCardSummaries.length > 0 && serviceModal && serviceModalTitle && serviceModalContent) {
  document.querySelectorAll(".service-card-expandable").forEach((card) => {
    card.open = false;
  });

  serviceCardSummaries.forEach((summary) => {
    summary.addEventListener("click", (event) => {
      event.preventDefault();

      const card = summary.closest(".service-card-expandable");
      const title = card?.querySelector("h3");
      const details = card?.querySelector(".service-card-details");

      if (!title || !details) {
        return;
      }

      serviceModalTitle.textContent = title.textContent ?? "";
      serviceModalContent.innerHTML = details.innerHTML;
      serviceModal.hidden = false;
      document.body.style.overflow = "hidden";
    });
  });

  serviceModalCloseTargets.forEach((target) => {
    target.addEventListener("click", closeServiceModal);
  });

  window.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !serviceModal.hidden) {
      closeServiceModal();
    }
  });
}

if ("IntersectionObserver" in window) {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.14 }
  );

  revealElements.forEach((element) => observer.observe(element));
}

if (partnersTrack && partnerLogos.length > 0) {
  const buildPartnerItem = (fileName) => {
    const card = document.createElement("div");
    const image = document.createElement("img");
    const partnerName = fileName.replace(/\.[^.]+$/, "").replace(/[_-]+/g, " ");

    card.className = "partner-logo-card";
    image.src = `img/parceiros/${fileName}`;
    image.alt = `Logo ${partnerName}`;
    image.loading = "lazy";

    card.appendChild(image);
    return card;
  };

  const marqueeItems = [...partnerLogos, ...partnerLogos];
  marqueeItems.forEach((fileName) => {
    partnersTrack.appendChild(buildPartnerItem(fileName));
  });
}
