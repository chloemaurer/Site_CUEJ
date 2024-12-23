document.addEventListener("DOMContentLoaded", function () {
  const intituler = document.querySelector(".intituler");
  const chapitreId = intituler.getAttribute("data-chapitre");
  const logo = document.querySelector("#logo");
  const continue_reading = document.querySelector(".continue_reading");

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
    if (continue_reading) continue_reading.style.backgroundColor = chapitreColors[chapitreId];
    document.querySelectorAll(".encadre").forEach((element) => {
      element.style.backgroundColor = chapitreColors[chapitreId];
    });

    document.querySelectorAll(".continue_reading").forEach((element) => {
      element.style.backgroundColor = chapitreColors[chapitreId];
    });

    document.querySelectorAll(".exergue_c").forEach((element) => {
      element.style.color = chapitreColors[chapitreId];
    });

    document.querySelectorAll(".bgChapitre").forEach((element) => {
      element.style.backgroundColor = chapitreColors[chapitreId];
    });

    document.querySelectorAll(".blockquote").forEach((element) => {
      element.style.color = chapitreColors[chapitreId];
    });

    document.querySelectorAll(".podcast").forEach((element) => {
      element.style.backgroundColor = chapitreColors[chapitreId];
    });

    document.querySelectorAll(".podcast_meta").forEach((element) => {
      element.style.backgroundColor = chapitreColors[chapitreId];
    });

    document.querySelectorAll(".podcast_meta").forEach((element) => {
      element.style.backgroundColor = chapitreColors[chapitreId];
    });

  }

});