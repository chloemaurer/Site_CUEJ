document.addEventListener("DOMContentLoaded", function () {
  const intituler = document.querySelector(".intituler");
  const chapitreId = intituler.getAttribute("data-chapitre");
  const logo = document.querySelector("#logo");
  const encadre = document.querySelector(".encadre");
  const exergue_c = document.querySelector(".exergue_c");
  const continue_reading = document.querySelector(".continue_reading");

  // Couleurs spécifiques par chapitre
  const chapitreColors = {
    1: "#6a7920",
    2: "#6189a4",
    3: "#8a151b",
  };

  // Appliquez la couleur
  if (chapitreColors[chapitreId]) {
    intituler.style.backgroundColor = chapitreColors[chapitreId];
    logo.style.backgroundColor = chapitreColors[chapitreId];
    encadre.style.backgroundColor = chapitreColors[chapitreId];
    continue_reading.style.backgroundColor = chapitreColors[chapitreId];
    exergue_c.style.color = chapitreColors[chapitreId];
    
  }

});




