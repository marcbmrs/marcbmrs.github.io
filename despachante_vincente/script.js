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
const reviewsTrack = document.querySelector("[data-reviews-track]");
const reviewsPrev = document.querySelector("[data-reviews-prev]");
const reviewsNext = document.querySelector("[data-reviews-next]");

const partnerLogos = [
  "CPFL.png",
  "RGE.png",
  "ceee-equatorial.png",
  "ouro_e_prata.png",
  "unesul.png",
  "catarinense.png",
  "jbs.png",
  "edisa.png",
  "triumph.jpg",
  "savauto.png",
  "maximmotors.jpg",
  "kinto.png",
  "leaseplan.png",
  "lm.png",
  "pontual.png",
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

if (reviewsTrack && reviewsPrev && reviewsNext) {
  let currentIndex = 0;
  let reviewsAutoPlay;

  const getCardsPerView = () => {
    if (window.innerWidth <= 760) return 1;
    if (window.innerWidth <= 1080) return 2;
    return 3;
  };

  const updateReviewsCarousel = () => {
    const cards = Array.from(reviewsTrack.children);
    const cardsPerView = getCardsPerView();
    const maxIndex = Math.max(0, cards.length - cardsPerView);
    currentIndex = Math.min(currentIndex, maxIndex);

    const firstCard = cards[0];
    if (!firstCard) return;

    const cardWidth = firstCard.getBoundingClientRect().width;
    const gap = 18;
    const offset = currentIndex * (cardWidth + gap);
    reviewsTrack.style.transform = `translateX(-${offset}px)`;
  };

  const goToNextReview = () => {
    const cardsPerView = getCardsPerView();
    const maxIndex = Math.max(0, reviewsTrack.children.length - cardsPerView);
    currentIndex = currentIndex >= maxIndex ? 0 : currentIndex + 1;
    updateReviewsCarousel();
  };

  const startReviewsAutoPlay = () => {
    window.clearInterval(reviewsAutoPlay);
    reviewsAutoPlay = window.setInterval(goToNextReview, 5000);
  };

  reviewsPrev.addEventListener("click", () => {
    currentIndex = Math.max(0, currentIndex - 1);
    updateReviewsCarousel();
    startReviewsAutoPlay();
  });

  reviewsNext.addEventListener("click", () => {
    goToNextReview();
    startReviewsAutoPlay();
  });

  reviewsTrack.addEventListener("mouseenter", () => window.clearInterval(reviewsAutoPlay));
  reviewsTrack.addEventListener("mouseleave", startReviewsAutoPlay);
  reviewsTrack.addEventListener("touchstart", () => window.clearInterval(reviewsAutoPlay), { passive: true });
  reviewsTrack.addEventListener("touchend", startReviewsAutoPlay, { passive: true });

  window.addEventListener("resize", updateReviewsCarousel);
  window.addEventListener("load", updateReviewsCarousel);
  window.addEventListener("load", startReviewsAutoPlay);
}
