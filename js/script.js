function showCategory(category) {
    document.getElementById('home').classList.remove('active');
    document.getElementById('category-view').classList.add('active');
}

function showHome() {
    document.getElementById('category-view').classList.remove('active');
    document.getElementById('home').classList.add('active');
}
