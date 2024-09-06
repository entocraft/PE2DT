<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT Dashboard</title>
    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <button class="button" id="startButton">Démarrer le compte à rebours</button>
            <button class="button" id="stopButton">Stopper et réinitialiser</button>
            <script>
                // Passer la valeur de raceTimeInMinutes depuis PHP vers JavaScript
                let raceTimeInMinutesFromPHP = <?php echo $raceTime; ?>;
            </script>
            <script src="countdown.js"></script>
        </div>
        <div class="bento-item">
            <h3>Relais control</h3>
            <button class="button" id="startRelayButton">Démarrer Relais</button>
            <button class="button" id="stopRelayButton">Arrêter Relais</button>
            <button class="button" id="startPitButton">Démarrer Pit</button>
            <button class="button" id="stopPitButton">Arrêter Pit</button>
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
        <div class="bento-item-large">
            
        </div>
        <div class="bento-item-large">
            <iframe class="axiframe" width="100%" src="https://www.apex-timing.com/live-timing/kartingmuret/index.html" id="iFrameResizer0" scrolling="no" style="overflow: hidden; height: 530px;"></iframe>
        </div>
    </div>
    <script>
        document.getElementById('startRelayButton').addEventListener('click', startRelay);
        document.getElementById('stopRelayButton').addEventListener('click', stopRelay);
        document.getElementById('startPitButton').addEventListener('click', startPit);
        document.getElementById('stopPitButton').addEventListener('click', stopPit);

        let relayTimer;
        let pitTimer;

        function markRelayAsCompleted(relayId) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'mark_complete.php', true);  // Remplacez 'mark_relay_as_completed.php' par le nom de votre fichier PHP
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('relay_id=' + encodeURIComponent(relayId));
        }

        function startRelay() {
            saveEventToDatabase('relay_start');
            console.log('Relais démarré');
        }

        function stopRelay() {
            saveEventToDatabase('relay_stop');
            startPit();  // Lancer automatiquement le pit timer
            console.log('Relais arrêté');
        }


        function startPit() {
            saveEventToDatabase('pit_start');
        }

        function stopPit() {
            getCurrentRelayId(function(relayId) {
                markRelayAsCompleted(relayId);
            });
            saveEventToDatabase('pit_stop');
            startRelay();
        }



        function getRemainingTime() {
            let remainingTimeText = document.getElementById('timeRemaining').textContent;
            let timeParts = remainingTimeText.split(':');

            // Si le temps restant n'est pas formaté correctement, retourner 0
            if (timeParts.length !== 3) return 0;

            let hours = parseInt(timeParts[0], 10);
            let minutes = parseInt(timeParts[1], 10);
            let seconds = parseInt(timeParts[2], 10);

            // Convertir le temps en secondes
            return (hours * 3600) + (minutes * 60) + seconds;
        }

        function formatTime(seconds) {
            let hours = Math.floor(seconds / 3600);
            let minutes = Math.floor((seconds % 3600) / 60);
            let remainingSeconds = seconds % 60;

            // Ajouter des zéros devant les chiffres si nécessaire (format HH:MM:SS)
            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

            return hours + ":" + minutes + ":" + remainingSeconds;
        }

        function getCurrentRelayId(callback) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_relay_id.php', true);  // Remplacez 'get_relay_id.php' par le nom de votre fichier PHP
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    callback(response.relay_id);  // Appelez la fonction de rappel avec le relay_id
                }
            };
            xhr.send('race_id=' + encodeURIComponent(<?php echo $race_id; ?>));
        }

        function saveEventToDatabase(eventType) {
            getCurrentRelayId(function(relayId) {
                let timeRemainingInSeconds = getRemainingTime();
                let timeRemainingFormatted = formatTime(timeRemainingInSeconds);

                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'save_event.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('race_id=' + encodeURIComponent(<?php echo $race_id; ?>) + '&event_type=' + encodeURIComponent(eventType) + '&time_remaining=' + encodeURIComponent(timeRemainingFormatted) + '&relay_id=' + encodeURIComponent(relayId));
            });
        }

    </script>
</div>
</body>

</html>
