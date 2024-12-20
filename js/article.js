document.addEventListener("DOMContentLoaded", function () {
  const intituler = document.querySelector(".intituler");
  const chapitreId = intituler.getAttribute("data-chapitre");

  // Couleurs spécifiques par chapitre
  const chapitreColors = {
    1: "#6a7920",
    2: "#6189a4",
    3: "#8a151b",
  };

  // Appliquez la couleur
  if (chapitreColors[chapitreId]) {
    intituler.style.backgroundColor = chapitreColors[chapitreId];
  }
});
