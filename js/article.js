
document.addEventListener("DOMContentLoaded", function () {
  const intituler = document.querySelector(".intituler");
  const chapitreId = intituler.getAttribute("data-chapitre");
  const logo = document.querySelector("#logo");
  const encadre = document.querySelector(".encadre");
  const exergue_c = document.querySelector(".exergue_c");
  const continue_reading = document.querySelector(".continue_reading");
  const bgChapitre = document.querySelector(".bgChapitre");

  // Couleurs spécifiques par chapitre
  const chapitreColors = {
    1: "#6a7920",
    2: "#6189a4",
    3: "#8a151b",
  };

  // Appliquez la couleur
  if (chapitreColors[chapitreId]) {
    if (intituler) intituler.style.backgroundColor = chapitreColors[chapitreId];
    if (logo) logo.style.backgroundColor = chapitreColors[chapitreId];
    if (encadre) encadre.style.backgroundColor = chapitreColors[chapitreId];
    if (continue_reading) continue_reading.style.backgroundColor = chapitreColors[chapitreId];
    if (exergue_c) exergue_c.style.color = chapitreColors[chapitreId];
    if (bgChapitre) bgChapitre.style.backgroundColor = chapitreColors[chapitreId];

  }

});