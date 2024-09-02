<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>PE2DT - Configuration de la Course</title>
    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="setup.css">
    <style>
        /* CSS temporaire pour tester l'affichage */
        #stintBar {
            border: 1px solid black;
            height: 50px;
            background-color: lightgray;
        }
        .stint-section {
            display: inline-block;
            height: 20px;
            width: 10%;
            margin-right: 2px;
        }

        /* Cacher la checkbox */
        .driver-card input[type="checkbox"] {
            display: none;
        }

        /* Style de sélection des pilotes */
        .driver-card.selected {
            border: 2px solid green;
            background-color: #e0ffe0; /* Optionnel : changer la couleur pour indiquer la sélection */
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-content">
            <h2>Configuration de la Course</h2>
            <!-- Formulaire commence ici -->
            <form action="traitement.php" method="post">

                <div class="setup-item">
                    <label for="raceName">Nom de la Course :</label>
                    <input type="text" id="raceName" name="raceName" required>
                </div>

                <div class="setup-item">
                    <label for="raceTime">Temps de Course (en minutes) :</label>
                    <input type="number" id="raceTime" name="raceTime" required>
                </div>

                <!-- Longueur de la Piste -->
                <div class="setup-item">
                    <label for="trackLength">Longueur de la Piste (en m) :</label>
                    <input type="number" id="trackLength" name="trackLength" required>
                </div>

                <!-- Température de l'Air -->
                <div class="setup-item">
                    <label for="airTemperature">Température de l'Air (°C) :</label>
                    <input type="number" id="airTemperature" name="airTemperature" step="0.1" required>
                </div>

                <!-- Température du Sol -->
                <div class="setup-item">
                    <label for="groundTemperature">Température du Sol (°C) :</label>
                    <input type="number" id="groundTemperature" name="groundTemperature" step="0.1" required>
                </div>

                <div class="setup-item">
                    <label>Sélectionner les Pilotes :</label>
                    <div id="driverSelection" class="driver-cards">
                        <?php
                            include "conn_sql.php";

                            $sql = "SELECT * FROM drivers";
                            $result = mysqli_query($conn, $sql);
                                    
                            while ($driver = mysqli_fetch_assoc($result)) {
                                echo "<div class='driver-card' data-driver-id='" . $driver['id'] . "'>" ;
                                echo "<img src='" . $driver['pic'] . "'>";
                                echo "<h2>". $driver['name'] . "</h2>";
                                echo "<h4>" . $driver['weight'] . " + " . $driver['lest'] . " kg</h4>";
                                echo "<input type='checkbox' name='selectedDrivers[]' value='" . $driver['id'] . "'>";
                                echo "</div>";
                            }
                        ?>
                    </div>
                </div>

                <!-- Bouton pour soumettre le formulaire -->
                <div class="setup-item">
                    <input type="submit" value="Soumettre">
                </div>

            </form>
            <!-- Formulaire se termine ici -->
        </div>
    </div>

    <script>
        // JavaScript pour gérer la sélection des pilotes
        document.querySelectorAll('.driver-card').forEach(function(card) {
            card.addEventListener('click', function() {
                // Toggle la classe 'selected'
                this.classList.toggle('selected');
                
                // Cocher ou décocher la checkbox associée
                let checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });
    </script>
</body>
</html>
