<?php
include "conn_sql.php";

if (isset($_POST['order'])) {
    $order = $_POST['order'];

    foreach ($order as $position => $id) {
        // Mettre à jour la position des pilotes dans la base de données
        $sql = "UPDATE race_driver SET driver_order = $position WHERE driver_id = $id";
        mysqli_query($conn, $sql);
    }

}
?>
