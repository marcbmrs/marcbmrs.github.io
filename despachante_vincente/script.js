const menuToggle = document.querySelector(".menu-toggle");
const mobileMenu = document.querySelector("#mobile-menu");
const mobileLinks = document.querySelectorAll("#mobile-menu a");
const revealElements = document.querySelectorAll(".reveal");
const partnersTrack = document.querySelector("[data-partners-track]");

const partnerLogos = [
  "ccr.png",
  "CPFL.png",
  "edisa.png",
  "fedex.png",
  "Forza.png",
  "kinto.png",
  "leaseplan.png",
  "lm.png",
  "locamerica.png",
  "ouro_e_prata.png",
  "ouroverde.png",
  "RGE.png",
  "savauto.png",
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
