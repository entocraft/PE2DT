// Récupérer le temps de course en minutes
let raceTimeInMinutes = raceTimeInMinutesFromPHP;

// Convertir les minutes en secondes pour le minuteur
let raceTimeInSeconds = raceTimeInMinutes * 60;

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
    let timer = duration;

    let interval = setInterval(function () {
        let hours = Math.floor(timer / 3600);
        let minutes = Math.floor((timer % 3600) / 60);
        let seconds = Math.floor(timer % 60);

        // Ajouter des zéros devant les chiffres si nécessaire (format HH:MM:SS)
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Mettre à jour l'affichage
        document.getElementById('timeRemaining').textContent = hours + ":" + minutes + ":" + seconds;

        // Réduire le temps d'une seconde
        if (--timer < 0) {
            clearInterval(interval); // Arrêter le minuteur quand il atteint 0
            timer = 0;  // Le minuteur reste à 00:00:00
        }
    }, 1000);  // Mettre à jour chaque seconde
}

// Afficher le temps total dès que la page est chargée
window.onload = function () {
    displayTime(raceTimeInSeconds); // Afficher le temps total au format HH:MM:SS

    // Démarrer le compte à rebours lorsque le bouton est cliqué
    document.getElementById('startButton').addEventListener('click', function() {
        startCountdown(raceTimeInSeconds);
        this.disabled = true;  // Désactiver le bouton après avoir démarré le compte à rebours
    });
};
