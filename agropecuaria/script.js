// Menu responsivo
const menuToggle = document.getElementById("menu-toggle");
const navLinks = document.querySelector(".nav-links");

menuToggle.addEventListener("click", () => {
  navLinks.classList.toggle("ativo");
});

// Carrossel de depoimentos
let index = 0;
const depoimentos = document.querySelectorAll(".depoimento");

function mostrarDepoimento() {
  depoimentos.forEach((dep, i) => {
    dep.classList.toggle("ativo", i === index);
  });
  index = (index + 1) % depoimentos.length;
}

setInterval(mostrarDepoimento, 4000);
