const nav = document.getElementById("nav");
const menu = document.getElementById("menu");
const closeMenu = document.getElementById("closeMenu");

nav.addEventListener("click", () => {
    menu.classList.add("active");
});

closeMenu.addEventListener("click", () => {
    menu.classList.remove("active");
});