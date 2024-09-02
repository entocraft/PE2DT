<?php
// Inclure la connexion à la base de données
include 'conn_sql.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer et valider les données
    $raceName = isset($_POST['raceName']) ? trim($_POST['raceName']) : '';
    $raceTime = isset($_POST['raceTime']) ? intval($_POST['raceTime']) : 0;
    $trackLength = isset($_POST['trackLength']) ? intval($_POST['trackLength']) : 0;
    $airTemperature = isset($_POST['airTemperature']) ? floatval($_POST['airTemperature']) : 0.0;
    $groundTemperature = isset($_POST['groundTemperature']) ? floatval($_POST['groundTemperature']) : 0.0;
    $selectedDrivers = isset($_POST['selectedDrivers']) ? $_POST['selectedDrivers'] : [];

    // Vérifier les valeurs
    if (empty($raceName) || $raceTime <= 0 || $trackLength <= 0 || $airTemperature < -100 || $groundTemperature < -100 || empty($selectedDrivers)) {
        die("Veuillez remplir tous les champs correctement et sélectionner au moins un pilote.");
    }

    // Préparer l'insertion dans la table des courses (race)
    $sqlRace = "INSERT INTO race (name, race_time, track_length, air_temperature, ground_temperature) VALUES (?, ?, ?, ?, ?)";
    $stmtRace = $conn->prepare($sqlRace);
    $stmtRace->bind_param("siiii", $raceName, $raceTime, $trackLength, $airTemperature, $groundTemperature);
    
    // Exécuter l'insertion
    if ($stmtRace->execute()) {
        // Récupérer l'ID de la course insérée
        $raceId = $stmtRace->insert_id;

        // Insérer les pilotes sélectionnés dans la table de liaison entre les courses et les pilotes
        $sqlDriverRace = "INSERT INTO race_driver (race_id, driver_id) VALUES (?, ?)";
        $stmtDriverRace = $conn->prepare($sqlDriverRace);

        // Insérer chaque pilote
        foreach ($selectedDrivers as $driverId) {
            $stmtDriverRace->bind_param("ii", $raceId, $driverId);
            $stmtDriverRace->execute();
        }

        // Fermer les statements
        $stmtDriverRace->close();
        $stmtRace->close();

        echo "Course et pilotes enregistrés avec succès.";
        echo "" . $raceName . "";
        echo "" . $selectedDrivers . "";
        header("Location: dashboard.php?id=" . $raceId);
    } else {
        echo "Erreur lors de l'enregistrement de la course : " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
