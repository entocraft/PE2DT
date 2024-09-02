<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT Dashboard</title>
    <link rel="stylesheet" type="text/css" href="common.css">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
</head>

<body>
    <div class="nav">
        <a href="index.php">Accueil</a>
        <a href="course.php">Course</a>
        <a href="pilotes.php">Pilotes</a>
        <a href="resultats.php">Résultats</a>
        <a href="parametres.php">Paramètres</a>
    </div>

    <?php
        include "conn_sql.php";

        $sql = "SELECT * FROM race_driver";
        $resultdata = mysqli_query($conn, $sql);
                        
        while ($driver = mysqli_fetch_assoc($resultdata)) {
            $d_id = $resultdata['driver_id'];
            echo $d_id;
            $sql = "SELECT * WHERE $d_id FROM drivers";
            $result = mysqli_query($conn, $sql);
                        
            while ($driver = mysqli_fetch_assoc($result)) {
            
            }
        }
    ?>

</body>

</html>