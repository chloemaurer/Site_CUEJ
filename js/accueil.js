const nav = document.getElementById("nav");
const menu = document.getElementById("menu");
const closeMenu = document.getElementById("closeMenu");

nav.addEventListener("click", () => {
  menu.classList.add("active");
});

closeMenu.addEventListener("click", () => {
  menu.classList.remove("active");
});

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
    chapitreSection.classList.remove("d-none");
    chapitreSection.classList.add("d-flex");
    chapitreImage.classList.add("active"); // Ajoute la bordure blanche à l'image cliquée
  });

  chapitreXIcon.addEventListener("click", () => {
    chapitreSection.classList.remove("d-flex");
    chapitreSection.classList.add("d-none");
    chapitreImage.classList.remove("active"); // Supprime la bordure blanche lorsque le chapitre est fermé
  });
});
