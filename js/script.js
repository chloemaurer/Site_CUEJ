console.log("Le JavaScript fonctionne !");

const menuIcon = document.getElementById("menu-icon");
const menuNav = document.getElementById("menu-nav");
const xIcon = document.getElementById("x-icon");

menuIcon.addEventListener("click", () => {
  if (menuNav.classList.contains("d-none")) {
    menuNav.classList.remove("d-none");
    menuNav.classList.add("d-flex");
  } else {
    menuNav.classList.add("d-none");
    menuNav.classList.remove("d-flex");
  }
});

xIcon.addEventListener("click", () => {
  if (menuNav.classList.contains("d-none")) {
    menuNav.classList.remove("d-none");
    menuNav.classList.add("d-flex");
  } else {
    menuNav.classList.add("d-none");
    menuNav.classList.remove("d-flex");
  }
});
