document.getElementById("edito-btn").addEventListener("click", function () {
  document.getElementById("edito").style.top = "0"; // Slide-down animation
});

document.getElementById("edito").addEventListener("click", function (e) {
  if (e.target === this) {
    this.style.top = "-100%"; // Fermer en remontant
  }
});

function openChapterSection(chapterId) {
  // Fermer toutes les sections ouvertes
  document.querySelectorAll(".chapter-section").forEach((section) => {
    section.classList.remove("show");
  });
  // Afficher la section spécifique
  document.getElementById(chapterId).classList.add("show");
}

// Fonction pour fermer l'édito
function closeEdito() { 
  document.getElementById("edito").style.top = "-100%";
}

// Événements pour les chapitres
document.querySelector(".chapter-container:nth-child(2)").onclick = () => {
  closeEdito(); // Ferme l'édito
  openChapterSection("chapter1");
};
document.querySelector(".chapter-container:nth-child(3)").onclick = () => {
  closeEdito(); // Ferme l'édito
  openChapterSection("chapter2");
};
document.querySelector(".chapter-container:nth-child(4)").onclick = () => {
  closeEdito(); // Ferme l'édito
  openChapterSection("chapter3");
};

document
  .getElementById("edito-close-btn")
  .addEventListener("click", function () {
    closeEdito(); // Fermer l'Édito
  });

// Ajouter un événement pour chaque bouton de fermeture des chapitres
document.querySelectorAll(".close-chapter").forEach((button) => {
  button.addEventListener("click", function () {
    // Trouve la section parente et la cache
    const chapterSection = this.closest(".chapter-section");
    chapterSection.classList.remove("show");
  });
});

// Sélectionner tous les conteneurs de chapitres
const chapters = document.querySelectorAll(".chapter-container");
const body = document.body;

// Ajouter des événements pour chaque chapitre
chapters.forEach((chapter) => {
  // Changer l'image de fond au survol
  chapter.addEventListener("mouseenter", () => {
    const bgImage = chapter.getAttribute("data-bg");
    body.style.backgroundImage = bgImage;
  });

  // Réinitialiser l'image de fond lorsqu'on quitte le cercle
  chapter.addEventListener("mouseleave", () => {
    body.style.backgroundImage = "url(images/fond-acceuil.jpg)";
  });

  // Fermer l'édito lorsqu'on clique sur un chapitre
  chapter.addEventListener("click", closeEdito);
});

