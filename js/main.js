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

// Separate the audio controls so I can style them better.
(() => {
    const elementTop = document.createElement('div');
    const elementBottom = document.createElement('div');
    elementTop.classList.add('mejs-prepended-buttons');
    elementBottom.classList.add('mejs-appended-buttons');

    const controls = document.querySelector('.mejs__controls');
    controls.prepend(elementTop);
    controls.append(elementBottom);

    const controlsChildren = Array.from(controls.childNodes).filter(v => v.className.startsWith("mejs_"));

    controlsChildren.slice(0, 3).forEach(elem => {
        elementTop.append(elem)
    });

    controlsChildren.slice(3, controlsChildren.length).forEach(elem => {
        elementBottom.append(elem)
    })
})()


// Sélection des éléments liés au volume
var audioPlayer = document.querySelector('.green-audio-player');
var volumeBtn = audioPlayer.querySelector('.volume-btn');
var volumeControls = audioPlayer.querySelector('.volume-controls');
var volumeProgress = volumeControls.querySelector('.slider .progress');
var speaker = audioPlayer.querySelector('#speaker');

document.addEventListener('DOMContentLoaded', () => {
    // Votre code qui dépend de audioPlayer ici
});

// Mise à jour du volume
player.addEventListener('volumechange', updateVolume);

// Affichage ou masquage des contrôles de volume
volumeBtn.addEventListener('click', () => {
    volumeBtn.classList.toggle('open');
    volumeControls.classList.toggle('hidden');
});

// Gestion de la direction des contrôles de volume
window.addEventListener('resize', directionAware);

// Gestion des sliders
sliders.forEach(slider => {
    let pin = slider.querySelector('.pin');
    slider.addEventListener('click', window[pin.dataset.method]);
});

// Fonction pour mettre à jour le volume
function updateVolume() {
    volumeProgress.style.height = player.volume * 100 + '%';
    if (player.volume >= 0.5) {
        speaker.attributes.d.value = 'M14.667 0v2.747c3.853 1.146 6.666 4.72 6.666 8.946 0 4.227-2.813 7.787-6.666 8.934v2.76C20 22.173 24 17.4 24 11.693 24 5.987 20 1.213 14.667 0zM18 11.693c0-2.36-1.333-4.386-3.333-5.373v10.707c2-.947 3.333-2.987 3.333-5.334zm-18-4v8h5.333L12 22.36V1.027L5.333 7.693H0z';
    } else if (player.volume < 0.5 && player.volume > 0.05) {
        speaker.attributes.d.value = 'M0 7.667v8h5.333L12 22.333V1L5.333 7.667M17.333 11.373C17.333 9.013 16 6.987 14 6v10.707c2-.947 3.333-2.987 3.333-5.334z';
    } else if (player.volume <= 0.05) {
        speaker.attributes.d.value = 'M0 7.667v8h5.333L12 22.333V1L5.333 7.667';
    }
}

// Fonction pour obtenir le coefficient de volume
function getCoefficient(event) {
    let slider = getRangeBox(event);
    let rect = slider.getBoundingClientRect();
    let K = 0;
    if (slider.dataset.direction == 'horizontal') {
        let offsetX = event.clientX - slider.offsetLeft;
        let width = slider.clientWidth;
        K = offsetX / width;
    } else if (slider.dataset.direction == 'vertical') {
        let height = slider.clientHeight;
        var offsetY = event.clientY - rect.top;
        K = 1 - offsetY / height;
    }
    return K;
}

// Fonction pour changer le volume en fonction de l'interaction avec le slider
function changeVolume(event) {
    if (inRange(event)) {
        player.volume = getCoefficient(event);
    }
}

// Fonction pour ajuster la direction des contrôles de volume
function directionAware() {
    if (window.innerHeight < 250) {
        volumeControls.style.bottom = '-54px';
        volumeControls.style.left = '54px';
    } else if (audioPlayer.offsetTop < 154) {
        volumeControls.style.bottom = '-164px';
        volumeControls.style.left = '-3px';
    } else {
        volumeControls.style.bottom = '52px';
        volumeControls.style.left = '-3px';
    }
}

