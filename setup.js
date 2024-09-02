// Fonction pour mettre à jour la jauge en fonction de la position du clic
function updateThermometer(thermometer, fillElement, temperatureValueElement, event) {
    const rect = thermometer.getBoundingClientRect();
    const offsetX = event.clientX - rect.left; // Position du clic par rapport à la jauge
    const percentage = offsetX / rect.width; // Pourcentage du remplissage

    // Calculer la température en fonction du pourcentage
    const temperature = Math.round(percentage * 65);
    
    // Mettre à jour la largeur de la jauge thermomètre en pourcentage
    fillElement.style.width = (percentage * 100) + '%';

    // Mettre à jour l'affichage de la température
    temperatureValueElement.textContent = temperature + '°C';
}

// Interaction pour la température de l'air
const airThermometer = document.getElementById('airThermometer');
const airThermometerFill = document.getElementById('airThermometerFill');
const airTemperatureValue = document.getElementById('airTemperatureValue');

airThermometer.addEventListener('mousedown', function(event) {
    updateThermometer(airThermometer, airThermometerFill, airTemperatureValue, event);

    // Permettre le glissement pour ajuster la température
    document.addEventListener('mousemove', onMouseMoveAir);
    document.addEventListener('mouseup', onMouseUpAir);
});

function onMouseMoveAir(event) {
    updateThermometer(airThermometer, airThermometerFill, airTemperatureValue, event);
}

function onMouseUpAir() {
    document.removeEventListener('mousemove', onMouseMoveAir);
    document.removeEventListener('mouseup', onMouseUpAir);
}

// Interaction pour la température du sol
const groundThermometer = document.getElementById('groundThermometer');
const groundThermometerFill = document.getElementById('groundThermometerFill');
const groundTemperatureValue = document.getElementById('groundTemperatureValue');

groundThermometer.addEventListener('mousedown', function(event) {
    updateThermometer(groundThermometer, groundThermometerFill, groundTemperatureValue, event);

    // Permettre le glissement pour ajuster la température
    document.addEventListener('mousemove', onMouseMoveGround);
    document.addEventListener('mouseup', onMouseUpGround);
});

function onMouseMoveGround(event) {
    updateThermometer(groundThermometer, groundThermometerFill, groundTemperatureValue, event);
}

function onMouseUpGround() {
    document.removeEventListener('mousemove', onMouseMoveGround);
    document.removeEventListener('mouseup', onMouseUpGround);
}