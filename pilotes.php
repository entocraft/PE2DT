<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>PE2DT - Pilotes</title>
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
            <!-- Créer un pilote -->
            <div class="bento-item">
                <h3>Créer un Pilote</h3>
                <form id="createPilotForm">
                    <label for="createName">Nom :</label><br>
                    <input type="text" id="createName" name="createName"><br>

                    <label for="createAge">Âge :</label><br>
                    <input type="number" id="createAge" name="createAge"><br>

                    <label for="createWeight">Poids :</label><br>
                    <input type="number" id="createWeight" name="createWeight"><br>

                    <label for="createLest">Lest :</label><br>
                    <input type="number" id="createLest" name="createLest"><br>

                    <label for="createPhoto">Photo :</label><br>
                    <input type="file" id="createPhoto" name="createPhoto"><br><br>

                    <input type="submit" value="Créer le Pilote">
                </form>
            </div>

            <!-- Modifier un pilote -->
            <div class="bento-item">
                <h3>Modifier un Pilote</h3>
                <form id="editPilotForm">
                    <label for="selectPilot">Sélectionner un Pilote :</label><br>
                    <select id="selectPilot" name="selectPilot">
                        <!-- Options de pilote à remplir dynamiquement -->
                        <option value="pilot1">Pilote 1</option>
                        <option value="pilot2">Pilote 2</option>
                    </select><br><br>

                    <label for="editName">Nom :</label><br>
                    <input type="text" id="editName" name="editName"><br>

                    <label for="editAge">Âge :</label><br>
                    <input type="number" id="editAge" name="editAge"><br>

                    <label for="editWeight">Poids :</label><br>
                    <input type="number" id="editWeight" name="editWeight"><br>

                    <label for="editLest">Lest :</label><br>
                    <input type="number" id="editLest" name="editLest"><br>

                    <label for="editPhoto">Photo :</label><br>
                    <input type="file" id="editPhoto" name="editPhoto"><br><br>

                    <input type="submit" value="Modifier le Pilote">
                </form>
            </div>

            <!-- Liste des pilotes -->
            <div class="bento-item large">
                <h3>Liste des Pilotes</h3>
                <ul id="pilotList">
                    <!-- La liste des pilotes sera affichée ici -->
                    <li>Pilote 1 - Âge: 30, Poids: 70kg, Lest: 10kg</li>
                    <li>Pilote 2 - Âge: 25, Poids: 68kg, Lest: 12kg</li>
                    <!-- Ajouter dynamiquement d'autres pilotes -->
                </ul>
            </div>
        </div>
    </div>
</body>

</html>
