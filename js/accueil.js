const nav = document.getElementById("nav");
const menu = document.getElementById("menu");
const closeMenu = document.getElementById("closeMenu");

nav.addEventListener("click", () => {
  menu.classList.add("active");
});

closeMenu.addEventListener("click", () => {
  menu.classList.remove("active");
});

// ------------------------------SECTION DECOUVRIR----------------------------//

const chapitreImages = document.querySelectorAll(".chapitre-image"); // Bulles d'images
const chapitreDetails = document.querySelector(".chapitre-details"); // Section détails
const xIcon = document.getElementById("x-icon");

chapitreImages.forEach((image) => {
  image.addEventListener("click", () => {
    // Afficher la section détails
    chapitreDetails.classList.remove("d-none");
    chapitreDetails.classList.add("d-flex");
  });
});

xIcon.addEventListener("click", () => {
  menu.classList.remove("d-flex");
  chapitreDetails.classList.add("d-none");
});
