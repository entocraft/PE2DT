<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT Dashboard</title>

    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
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

                        while ($driver = mysqli_fetch_assoc($result)) {
                            echo "<li class='driver-item' data-id='" . $driver['id'] . "'>";
                            echo "<h2>" . $driver['name'] . "</h2>";
                            echo "<input type='number' class='relay-duration' placeholder='Durée du relais (minutes)' />";
                            echo "<input type='number' class='pit-duration' placeholder='Durée du pit (minutes)' />";
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

                    $sql = "SELECT * FROM relays";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        die("Erreur SQL : " . mysqli_error($conn));
                    }

                    while ($relay = mysqli_fetch_assoc($result)) {
                        $did =  $relay['driver_id'];
                        $sqlDriver = "SELECT * FROM drivers WHERE id = $did";
                        $resultdriver = mysqli_query($conn, $sqlDriver);

                        if ($resultdriver && $seldriver = mysqli_fetch_assoc($resultdriver)) {
                            echo "<li class='selected-driver-item' data-id='" . $relay['driver_id'] . "' data-duration='" . $relay['time'] . "'>";
                            echo "<h3>" . $seldriver['name'] . " - " . $relay['time'] . " min | pit : " . $relay['pit_time'] . " min</h3>";
                            echo "<button class='remove-driver'>Retirer</button>";
                            echo "</li>";
                        }
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
    var raceId = <?php echo $race_id; ?>;

    $(".add-driver").click(function() {
        var driverItem = $(this).closest('.driver-item');
        var driverId = driverItem.data('id');
        var driverName = driverItem.find('h2').text();
        var relayduration = driverItem.find('.relay-duration').val();
        var pitduration = driverItem.find('.pit-duration').val();

        if (relayduration === '' || relayduration <= 0) {
            alert('Veuillez entrer une durée valide pour le relais.');
            return;
        }

        if (pitduration === '' || pitduration <= 0) {
            alert('Veuillez entrer une durée valide pour le pit.');
            return;
        }

        var selectedItem = "<li class='selected-driver-item' data-id='" + driverId + "' data-duration='" + relayduration + "'>";
        selectedItem += "<h3 class='new'>" + driverName + " - " + relayduration + " min</h3>";
        selectedItem += "<button class='remove-driver'>Retirer</button>";
        selectedItem += "</li>";
        $("#selected-driver-list").append(selectedItem);

        $.ajax({
            url: 'update_relays.php',
            method: 'POST',
            data: {
                action: 'save',
                race_id: raceId,
                driver_id: driverId,
                relay_duration: relayduration,
                pit_duration: pitduration,
            },
            success: function(response) {
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Erreur: " + textStatus + " - " + errorThrown);
            }
        });
    });

    $(document).on('click', '.remove-driver', function() {
        var driverItem = $(this).closest('.selected-driver-item');
        var driverId = driverItem.data('id');
        
        driverItem.remove();

        $.ajax({
            url: 'update_relays.php',
            method: 'POST',
            data: {
                action: 'remove',
                race_id: raceId,
                driver_id: driverId
            },
            success: function(response) {
                console.log(response);
            }
        });
    });

    $("#selected-driver-list").sortable({
        update: function(event, ui) {
            var order = [];
            $("#selected-driver-list .selected-driver-item").each(function(index) {
                var driverId = $(this).data('id');
                order.push({driver_id: driverId, order: index + 1});
            });
            
            $.ajax({
                url: 'update_relays.php',
                method: 'POST',
                data: {
                    action: 'save',
                    race_id: raceId,
                    order: order
                },
                success: function(response) {
                    console.log(response);
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