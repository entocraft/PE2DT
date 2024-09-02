<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT Dashboard</title>
    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
</head>

<body>

<?php
    include 'conn_sql.php';

    $race_id = $_GET['id'];

    $sql = "SELECT race_time FROM race WHERE race_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $race_id);
    $stmt->execute();
    $stmt->bind_result($raceTime);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
?>

<div class="nav">
    <?php
        echo "<a href='index.php?id=" . $race_id . "'>Accueil</a>";
        echo "<a href='course.php?id=" . $race_id . "'>Course</a>";
        echo "<a href='pilotes.php?id=" . $race_id . "'>Pilotes</a>";
        echo "<a href='resultats.php?id=" . $race_id . "'>Résultats</a>";
        echo "<a href='parametres.php?id=" . $race_id . "'>Paramètres</a>";
    ?>
    </div>

    <div class="dashboard">
        <div class="bento">
            <div class="bento-item">
                <h3>Temps de course restant</h3>
                <p id="timeRemaining">--:--:--</p>
                <button id="startButton">Démarrer le compte à rebours</button>
                <script>
                    // Passer la valeur de raceTimeInMinutes depuis PHP vers JavaScript
                    let raceTimeInMinutesFromPHP = <?php echo $raceTime; ?>;
                </script>
                <script src="countdown.js"></script>
            </div>
            <div class="bento-item">
                <h3>Pilote actuel</h3>
                <p id="currentDriver">Nom du Pilote</p>
            </div>
            <div class="bento-item">
                <h3>Pilote suivant</h3>
                <p id="nextDriver">Nom du Pilote</p>
            </div>
            <div class="bento-item">
                <h3>Relais restants</h3>
                <p id="stintsRemaining">0</p>
            </div>
            <div class="bento-item">
                <h3>Relais passés</h3>
                <p id="stintsCompleted">0</p>
            </div>
        </div>
    </div>
</body>

</html>
