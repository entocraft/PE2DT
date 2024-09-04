<?php
include "conn_sql.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $race_id = $_POST['race_id'];

    // Debugging: afficher les données reçues
    var_dump($_POST);

    if ($action == 'save') {
        // Mettre à jour l'ordre et la durée des relais
        $order = $_POST['order'];

        foreach ($order as $item) {
            $driver_id = $item['driver_id'];
            $driver_order = $item['order'];
            $duration = $item['duration'];

            // Vérifier si le relais existe déjà
            $sql = "SELECT * FROM relays WHERE race_id = $race_id AND driver_id = $driver_id";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Erreur SQL : " . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                // Mettre à jour le relais existant
                $sql = "UPDATE relays SET driver_order = $driver_order, time = $duration WHERE race_id = $race_id AND driver_id = $driver_id";
            } else {
                // Insérer un nouveau relais
                $sql = "INSERT INTO relays (race_id, driver_id, driver_order, time) VALUES ($race_id, $driver_id, $driver_order, $duration)";
            }

            $update_result = mysqli_query($conn, $sql);

            if (!$update_result) {
                die("Erreur SQL lors de l'enregistrement du relais : " . mysqli_error($conn));
            }
        }

        echo "Relais sauvegardés avec succès!";
    } elseif ($action == 'remove') {
        // Supprimer un relais
        $driver_id = $_POST['driver_id'];
        $sql = "DELETE FROM relays WHERE race_id = $race_id AND driver_id = $driver_id";
        $delete_result = mysqli_query($conn, $sql);

        if (!$delete_result) {
            die("Erreur SQL lors de la suppression du relais : " . mysqli_error($conn));
        }

        echo "Relais supprimé avec succès!";
    }
}
?>
