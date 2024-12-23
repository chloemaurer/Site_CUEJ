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
};

// Sélectionne tous les éléments audio sur la page
const audioElements = document.querySelectorAll("audio");

// Initialise MediaElementPlayer pour chaque élément audio
audioElements.forEach((audio) => {
    new MediaElementPlayer(audio, options);

    // Personnalise les contrôles pour cet élément audio
    const elementTop = document.createElement('div');
    const elementBottom = document.createElement('div');
    elementTop.classList.add('mejs-prepended-buttons');
    elementBottom.classList.add('mejs-appended-buttons');

    const controls = audio.closest('.mejs-container').querySelector('.mejs__controls');
    controls.prepend(elementTop);
    controls.append(elementBottom);

    const controlsChildren = Array.from(controls.childNodes).filter(v => v.className.startsWith("mejs_"));

    controlsChildren.slice(0, 3).forEach(elem => {
        elementTop.append(elem);
    });

    controlsChildren.slice(3, controlsChildren.length).forEach((elem) => {
        elementBottom.append(elem);
    });
});
