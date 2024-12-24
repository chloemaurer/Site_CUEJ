"use strict";

const options = {
    defaultSpeed: '1.00',
    speeds: ['1.25', '1.50', '2.00', '0.75'],
    loop: true,
    skipBackInterval: 15,
    jumpForwardInterval: 15,
    features: [
        "playpause",
        "progress",
        "current",
        "duration",
        "skipback",
        "changespeed",
        "volume",
        "jumpforward",
    ]
}

new MediaElementPlayer(
    document.querySelector("audio"),
    options
);

// Réorganise les contrôles pour chaque audio
document.addEventListener("DOMContentLoaded", () => {
    const players = document.querySelectorAll(".mejs__controls");
    players.forEach(controls => {
        const elementTop = document.createElement('div');
        const elementBottom = document.createElement('div');
        elementTop.classList.add('mejs-prepended-buttons');
        elementBottom.classList.add('mejs-appended-buttons');

        controls.prepend(elementTop);
        controls.append(elementBottom);

        const controlsChildren = Array.from(controls.childNodes).filter(v => v.className.startsWith("mejs_"));

        controlsChildren.slice(0, 3).forEach(elem => {
            elementTop.append(elem);
        });

        controlsChildren.slice(3, controlsChildren.length).forEach(elem => {
            elementBottom.append(elem);
        });
    });
});





function showVideo(element) {
    // Récupère le conteneur parent
    const container = element.closest('div');

    // Récupère l'image de superposition
    const overlayImage = container.querySelector('.video-overlay');

    // Récupère le bouton de lecture
    const playIcon = container.querySelector('.play-icon');

    // Récupère la vidéo
    const video = container.querySelector('video');

    // Masque l'image et le bouton
    if (overlayImage) {
        overlayImage.style.display = 'none';
    }
    if (playIcon) {
        playIcon.style.display = 'none';
    }

    // Affiche et lance la vidéo
    if (video) {
        video.style.display = 'block';
        video.play();
    }
}



