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





// Tableau pour stocker les pilotes sélectionnés
let selectedDrivers = [];

// Fonction pour gérer la sélection des pilotes
function toggleDriverSelection(event) {
    const card = event.currentTarget;
    const driver = card.getAttribute('data-driver');

    if (card.classList.contains('selected')) {
        card.classList.remove('selected');
        selectedDrivers = selectedDrivers.filter(d => d !== driver);
    } else {
        card.classList.add('selected');
        selectedDrivers.push(driver);
    }
}

// Fonction pour calculer et visualiser les relais
function calculateStints() {
    const stintBar = document.getElementById('stintBar');
    stintBar.innerHTML = ''; // Vider la barre avant de la remplir

    // Calcul simple : répartir équitablement les relais entre les pilotes sélectionnés
    const totalStints = 10; // Par exemple, 10 relais à répartir
    const stintsPerDriver = Math.floor(totalStints / selectedDrivers.length);
    const remainderStints = totalStints % selectedDrivers.length;

    // Répartir les relais
    selectedDrivers.forEach(driver => {
        for (let i = 0; i < stintsPerDriver; i++) {
            const section = document.createElement('div');
            section.classList.add('stint-section', driver);
            stintBar.appendChild(section);
        }
    });

    // Répartir les relais restants
    for (let i = 0; i < remainderStints; i++) {
        const section = document.createElement('div');
        section.classList.add('stint-section', selectedDrivers[i]);
        stintBar.appendChild(section);
    }
}

// Ajouter un écouteur d'événement aux cartes de pilotes
document.querySelectorAll('.driver-card').forEach(card => {
    card.addEventListener('click', toggleDriverSelection);
});

// Ajouter un écouteur d'événement au bouton "Calculer les Relais"
document.getElementById('calculateStints').addEventListener('click', calculateStints);

document.addEventListener('DOMContentLoaded', function() {
    const driverSelection = document.getElementById('driverSelection');

    // Liste de couleurs prédéfinies
    const colors = ['#FF5733', '#33FF57', '#3357FF', '#F0F0F0', '#FF33A1', '#A1FF33', '#33FFF0', '#FFAB33'];

    // Fonction pour récupérer les pilotes depuis l'API
    async function fetchDrivers() {
        try {
            const response = await fetch('get_drivers.php'); // URL de votre script backend
            if (!response.ok) throw new Error('Network response was not ok');
            
            const drivers = await response.json();

            // Générer les cartes pour chaque pilote
            driverSelection.innerHTML = drivers.map((driver, index) => {
                const color = colors[index % colors.length]; // Assigner une couleur en fonction de l'index
                return `
                    <div class="driver-card" data-driver-id="${driver.id}" style="background-color: ${color};">
                        <img src="${driver.photo_url}" alt="${driver.name}">
                        <p>${driver.name}</p>
                    </div>
                `;
            }).join('');
        } catch (error) {
            console.error('Error fetching drivers:', error);
        }
    }

    fetchDrivers();
});
