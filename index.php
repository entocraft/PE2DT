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

    <div class="dashboard">
        <div class="bento">
            <div class="bento-item">
                <h3>Temps de course restant</h3>
                <p id="timeRemaining">--:--:--</p>
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
