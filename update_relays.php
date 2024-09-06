<?php
include "conn_sql.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $race_id = $_POST['race_id'];

    var_dump($_POST);

    if ($action == 'save') {
        if (isset($_POST['driver_id']) && isset($_POST['relay_duration']) && isset($_POST['pit_duration'])) {
            $driver_id = $_POST['driver_id'];
            $relay_duration = $_POST['relay_duration'];
            $pit_duration = $_POST['pit_duration'];

            $sql = "SELECT * FROM relays WHERE race_id = $race_id AND driver_id = $driver_id";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Erreur SQL : " . mysqli_error($conn));
            }

            $sql = "INSERT INTO relays (race_id, driver_id, time, pit_time) VALUES ($race_id, $driver_id, $relay_duration, $pit_duration)";

            $update_result = mysqli_query($conn, $sql);

            if (!$update_result) {
                die("Erreur SQL lors de l'enregistrement du relais : " . mysqli_error($conn));
            }

            echo "Relais sauvegardé avec succès!";
        }

        if (isset($_POST['order'])) {
            $order = $_POST['order'];

            foreach ($order as $item) {
                $driver_id = $item['driver_id'];
                $driver_order = $item['order'];

                $sql = "UPDATE relays SET driver_order = $driver_order WHERE race_id = $race_id AND driver_id = $driver_id";
                $update_result = mysqli_query($conn, $sql);

                if (!$update_result) {
                    die("Erreur SQL lors de l'enregistrement de l'ordre du relais : " . mysqli_error($conn));
                }
            }

            echo "Ordre des relais sauvegardé avec succès!";
        }
    } elseif ($action == 'remove') {
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
