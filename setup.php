<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT - Configuration de la Course</title>
    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="setup.css">
</head>

<?php

include "conn_sql.php";

$query = "SELECT id, name, pic, weight, lest, age FROM drivers";
$stmt = $conn->prepare($query);
$stmt->execute();

// Lier les résultats
$stmt->bind_result($id, $name, $pic, $weight, $lest, $age);

// Récupérer les résultats sous forme de tableau associatif
$drivers = [];
while ($stmt->fetch()) {
    $drivers[] = [
        'id' => $id,
        'name' => $name,
        'pic' => $pic,
        'weight' => $weight,
        'lest' => $lest,
        'age' => $age
    ];
}

// Fermer la déclaration et la connexion
$stmt->close();
$conn->close();

// Retourner les résultats en format JSON
echo json_encode($drivers);
?>

<body>
    <div class="setup-container">
        <div class="setup-content">
            <h2>Configuration de la Course</h2>

            <!-- Temps de Course -->
            <div class="setup-item">
                <label for="raceTime">Temps de Course (en heures) :</label>
                <input type="number" id="raceTime" name="raceTime">
            </div>

            <!-- Longueur de la Piste -->
            <div class="setup-item">
                <label for="trackLength">Longueur de la Piste (en km) :</label>
                <input type="number" id="trackLength" name="trackLength">
            </div>

            <!-- Jauge pour la Température de l'Air -->
            <div class="setup-item">
                <label>Température de l'Air (°C) :</label>
                <div class="thermometer-display" id="airThermometer">
                    <div class="thermometer-fill" id="airThermometerFill">
                        <span id="airTemperatureValue">20°C</span>
                    </div>
                </div>
            </div>

            <!-- Jauge pour la Température du Sol -->
            <div class="setup-item">
                <label>Température du Sol (°C) :</label>
                <div class="thermometer-display" id="groundThermometer">
                    <div class="thermometer-fill" id="groundThermometerFill">
                        <span id="groundTemperatureValue">30°C</span>
                    </div>
                </div>
            </div>

                    <div class="setup-item">
                        <label>Sélectionner les Pilotes pour les Relais :</label>
                        <div id="driverSelection" class="driver-cards">
                            <!-- Les cartes des pilotes seront insérées ici par JavaScript -->
                        </div>
                        <button id="calculateStints">Calculer les Relais</button>
                    </div>

            <!-- Visualisation des Relais -->
            <div class="setup-item">
                <label>Visualisation des Relais :</label>
                <div id="stintDetails">
                    <div class="stint-bar" id="stintBar">
                        <!-- Les sections de relais seront ajoutées ici -->
                    </div>
                </div>
            </div>

            <!-- Activation du Panneautage -->
            <div class="setup-item">
                <label for="enableSignaling">Activer le Panneautage :</label>
                <input type="checkbox" id="enableSignaling" name="enableSignaling">
            </div>

            <!-- Bouton de soumission -->
            <div class="setup-item">
                <button type="submit" id="submitSetup">Enregistrer la Configuration</button>
            </div>
        </div>
    </div>

    <script src="setup.js"></script>

</body>

</html>
