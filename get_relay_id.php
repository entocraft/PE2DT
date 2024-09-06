<?php
include 'conn_sql.php';

$race_id = $_POST['race_id'];

function getCurrentRelayId($conn, $race_id) {
    $sql = "SELECT id FROM relays WHERE race_id = ? AND do = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $race_id);
    $stmt->execute();
    $stmt->bind_result($relay_id);
    $stmt->fetch();
    $stmt->close();
    
    return $relay_id;
}

// Obtenez l'identifiant du relais en cours
$relay_id = getCurrentRelayId($conn, $race_id);

// Retourner le relay_id en tant que réponse JSON
echo json_encode(array('relay_id' => $relay_id));

$conn->close();
?>
