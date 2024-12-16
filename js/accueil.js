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

// const chapitre1Image = document.getElementById("chapitre1-image");
// const chapitre2Image = document.getElementById("chapitre2-image");
// const chapitre3Image = document.getElementById("chapitre3-image");

// const chapitre1 = document.getElementById("chapitre1");
// const chapitre2 = document.getElementById("chapitre2");
// const chapitre3 = document.getElementById("chapitre3");

// const xIcon1 = document.getElementById("x-icon1");
// const xIcon2 = document.getElementById("x-icon2");
// const xIcon3 = document.getElementById("x-icon3");

// chapitre1Image.addEventListener("click", () => {
//   // Afficher la section détails
//   chapitre1.classList.remove("d-none");
//   chapitre1.classList.add("d-flex");
// });

// chapitre2Image.addEventListener("click", () => {
//   // Afficher la section détails
//   chapitre2.classList.remove("d-none");
//   chapitre2.classList.add("d-flex");
// });

// chapitre3Image.addEventListener("click", () => {
//   // Afficher la section détails
//   chapitre3.classList.remove("d-none");
//   chapitre3.classList.add("d-flex");
// });

// xIcon1.addEventListener("click", () => {
//   chapitre1.classList.remove("d-flex");
//   chapitre1.classList.add("d-none");
// });

// xIcon2.addEventListener("click", () => {
//   chapitre2.classList.remove("d-flex");
//   chapitre2.classList.add("d-none");
// });

// xIcon3.addEventListener("click", () => {
//   chapitre3.classList.remove("d-flex");
//   chapitre3.classList.add("d-none");
// });

const chapitres = [
  { image: "chapitre1-image", section: "chapitre1", xIcon: "x-icon1" },
  { image: "chapitre2-image", section: "chapitre2", xIcon: "x-icon2" },
  { image: "chapitre3-image", section: "chapitre3", xIcon: "x-icon3" },
];

// Fonction pour masquer tous les chapitres
function hideAllChapitres() {
  chapitres.forEach(({ section, image }) => {
    const chapitre = document.getElementById(section);
    const chapitreImage = document.getElementById(image);
    chapitre.classList.remove("d-flex");
    chapitre.classList.add("d-none");
    chapitreImage.classList.remove("active"); // Supprime la bordure blanche
  });
}

// Ajouter les écouteurs dynamiquement
chapitres.forEach(({ image, section, xIcon }) => {
  const chapitreImage = document.getElementById(image);
  const chapitreSection = document.getElementById(section);
  const chapitreXIcon = document.getElementById(xIcon);

  chapitreImage.addEventListener("click", () => {
    hideAllChapitres(); // Masquer tous les chapitres
    chapitreSection.classList.toggle("d-none");
    chapitreSection.classList.toggle("d-flex");
    chapitreImage.classList.toggle("active"); // Ajoute la bordure blanche à l'image cliquée
  });

  chapitreXIcon.addEventListener("click", () => {
    chapitreSection.classList.remove("d-flex");
    chapitreSection.classList.add("d-none");
    chapitreImage.classList.remove("active"); // Supprime la bordure blanche lorsque le chapitre est fermé
  });
});
