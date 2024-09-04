<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT Dashboard</title>
    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="dashboard.css">

    <!-- Inclure jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Inclure jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    
    <!-- Inclure le CSS de jQuery UI pour le style -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body>
    <?php
        $race_id = $_GET['id'];
    ?>

    <div class="nav">
        <?php
            echo "<a href='dashboard.php?id=" . $race_id . "'>Accueil</a>";
            echo "<a href='course.php?id=" . $race_id . "'>Course</a>";
            echo "<a href='pilotes.php?id=" . $race_id . "'>Pilotes</a>";
            echo "<a href='resultats.php?id=" . $race_id . "'>Résultats</a>";
            echo "<a href='parametres.php?id=" . $race_id . "'>Paramètres</a>";
        ?>
    </div>

    
    <div class="dashboard">
        <div class="bento">
            <div class="bento-item-large">
                <h3>Pilote séléctionné</h3>
                <ul id="driver-list">
                    <!-- Liste des pilotes sera ici -->
                    <?php
                        include "conn_sql.php";

                        // Requête SQL pour obtenir les informations des pilotes directement
                        $sql = "
                            SELECT DISTINCT d.name, d.id 
                            FROM race_driver rd
                            JOIN drivers d ON rd.driver_id = d.id
                            ORDER BY rd.driver_order ASC
                        ";

                        $result = mysqli_query($conn, $sql);

                        if (!$result) {
                            die("Erreur SQL : " . mysqli_error($conn));
                        }

                        // Affichage des pilotes
                        while ($driver = mysqli_fetch_assoc($result)) {
                            echo "<li class='driver-item' data-id='" . $driver['id'] . "'>";
                            echo "<h2>" . $driver['name'] . "</h2>";
                            echo "<input type='number' class='relay-duration' placeholder='Durée du relais (minutes)' />";
                            echo "<button class='add-driver'>Ajouter ce pilote</button>";
                            echo "</li>";
                        }

                        if (mysqli_num_rows($result) == 0) {
                            echo "<p>Aucun pilote trouvé.</p>";
                        }
                    ?>
                </ul>
            </div>
            <div class="bento-item-large">
                <h3>Relais</h3>
                <ul id="selected-driver-list">
                <?php
                    include "conn_sql.php";

                    // Requête SQL pour obtenir les informations des relais directement
                    $sql = "SELECT * FROM relays";

                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        die("Erreur SQL : " . mysqli_error($conn));
                    }

                    // Affichage des relais
                    while ($relay = mysqli_fetch_assoc($result)) {
                        echo "<li class='selected-driver-item' data-id='" . $relay['driver_id'] . "' data-duration='" . $relay['time'] . "'>";
                        echo "<h2>" . $relay['name'] . " - " . $relay['time'] . " min</h2>";
                        echo "<button class='remove-driver'>Retirer</button>";
                        echo "</li>";
                    }

                    if (mysqli_num_rows($result) == 0) {
                        echo "<p>Aucun relais trouvé.</p>";
                    }
                ?>

                </ul>
                <button id="saveOrderButton">Valider</button>
            </div>
        </div>
    </div>

    <script>
$(function() {
    var raceId = <?php echo $race_id; ?>; // Obtenir l'ID de la course

    // Ajouter un pilote à la liste sélectionnée avec la durée du relais
    $(".add-driver").click(function() {
        var driverItem = $(this).closest('.driver-item');
        var driverId = driverItem.data('id');
        var driverName = driverItem.find('h2').text();
        var duration = driverItem.find('.relay-duration').val();

        if (duration === '' || duration <= 0) {
            alert('Veuillez entrer une durée valide pour le relais.');
            return;
        }

        // Ajouter le pilote et la durée à la liste sélectionnée
        var selectedItem = "<li class='selected-driver-item' data-id='" + driverId + "' data-duration='" + duration + "'>";
        selectedItem += "<h2>" + driverName + " - " + duration + " min</h2>";
        selectedItem += "<button class='remove-driver'>Retirer</button>";
        selectedItem += "</li>";
        $("#selected-driver-list").append(selectedItem);

        // Envoyer la requête AJAX pour ajouter le relais à la table relays
        $.ajax({
            url: 'update_relays.php',
            method: 'POST',
            data: {
                action: 'save',
                race_id: raceId,
                driver_id: driverId,
                duration: duration
            },
            success: function(response) {
                console.log(response); // Afficher la réponse dans la console
            }
        });
    });

    // Retirer un pilote de la liste sélectionnée
    $(document).on('click', '.remove-driver', function() {
        var driverItem = $(this).closest('.selected-driver-item');
        var driverId = driverItem.data('id');
        
        // Supprimer l'élément de la liste
        driverItem.remove();

        // Envoyer la requête AJAX pour supprimer le relais de la table relays
        $.ajax({
            url: 'update_relays.php',
            method: 'POST',
            data: {
                action: 'remove',
                race_id: raceId,
                driver_id: driverId
            },
            success: function(response) {
                console.log(response); // Afficher la réponse dans la console
            }
        });
    });

    // Rendre la liste réorganisable
    $("#selected-driver-list").sortable({
        update: function(event, ui) {
            // Récupérer l'ordre des pilotes
            var order = [];
            $("#selected-driver-list .selected-driver-item").each(function(index) {
                var driverId = $(this).data('id');
                order.push({driver_id: driverId, order: index + 1});
            });
            
            // Envoyer la requête AJAX pour mettre à jour l'ordre dans la table relays
            $.ajax({
                url: 'update_relays.php',
                method: 'POST',
                data: {
                    action: 'save',
                    race_id: raceId,
                    order: order
                },
                success: function(response) {
                    console.log(response); // Afficher la réponse dans la console pour vérifier
                    alert('Ordre des relais sauvegardé avec succès!');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Erreur: " + textStatus + " - " + errorThrown);
                }
            });

        }
    });
});
</script>



</body>

</html>