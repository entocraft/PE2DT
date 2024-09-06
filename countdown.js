// Convertir les minutes en secondes pour le minuteur
let raceTimeInSeconds = raceTimeInMinutesFromPHP * 60;
let timerInterval;

function displayTime(duration) {
    let hours = Math.floor(duration / 3600);
    let minutes = Math.floor((duration % 3600) / 60);
    let seconds = Math.floor(duration % 60);

    // Ajouter des zéros devant les chiffres si nécessaire (format HH:MM:SS)
    hours = hours < 10 ? "0" + hours : hours;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    // Mettre à jour l'affichage
    document.getElementById('timeRemaining').textContent = hours + ":" + minutes + ":" + seconds;
}

function startCountdown(duration) {
    let endTime = Date.now() + duration * 1000;  // Calculer l'heure de fin en millisecondes
    localStorage.setItem('endTime', endTime);    // Sauvegarder l'heure de fin dans localStorage

    timerInterval = setInterval(function () {
        let remainingTime = Math.floor((endTime - Date.now()) / 1000);

        if (remainingTime >= 0) {
            displayTime(remainingTime);
        } else {
            clearInterval(timerInterval);  // Arrêter le minuteur quand il atteint 0
            displayTime(0);            // Afficher 00:00:00
        }
    }, 1000);  // Mettre à jour chaque seconde
}

function stopCountdown() {
    clearInterval(timerInterval);  // Arrêter le compte à rebours
    localStorage.removeItem('endTime');  // Supprimer l'heure de fin du stockage local
    displayTime(raceTimeInSeconds);  // Réinitialiser l'affichage du temps
    document.getElementById('startButton').disabled = false;  // Réactiver le bouton de démarrage
}

// Afficher le temps total dès que la page est chargée
window.onload = function () {
    let savedEndTime = localStorage.getItem('endTime');

    if (savedEndTime) {
        let remainingTime = Math.floor((savedEndTime - Date.now()) / 1000);

        if (remainingTime > 0) {
            displayTime(remainingTime);
            startCountdown(remainingTime);  // Reprendre le compte à rebours
            document.getElementById('startButton').disabled = true;
        } else {
            displayTime(0);  // Le compte à rebours est terminé
        }
    } else {
        displayTime(raceTimeInSeconds);  // Afficher le temps total au format HH:MM:SS
    }

    // Démarrer le compte à rebours lorsque le bouton est cliqué
    document.getElementById('startButton').addEventListener('click', function() {
        startCountdown(raceTimeInSeconds);
        this.disabled = true;  // Désactiver le bouton après avoir démarré le compte à rebours
    });

    // Stopper et réinitialiser le compte à rebours lorsque le bouton est cliqué
    document.getElementById('stopButton').addEventListener('click', function() {
        stopCountdown();
    });
};
