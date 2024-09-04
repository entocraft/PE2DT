<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT - Pilotes</title>
    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
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
        <div class='container'>
            <?php
                include "conn_sql.php";

                $sql = "SELECT * FROM drivers";
                $result = mysqli_query($conn, $sql);

                while ($driver = mysqli_fetch_assoc($result)) {
                    echo "<div class='driver_card'>" ;
                    echo "<img class='pp' src='" . $driver['pic'] . "'>";
                    echo "<h2>". $driver['name'] . "</h2>";
                    echo "<h4>" . $driver['weight'] . " + " . $driver['lest'] . " kg</h4>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
</body>

</html>
