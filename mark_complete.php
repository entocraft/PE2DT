<?php
include 'conn_sql.php';

$relay_id = $_POST['relay_id'];

// Mettre à jour la colonne `do` lorsque le pilote passe au pit
$sql = "UPDATE relays SET do = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $relay_id);
$stmt->execute();
$stmt->close();
$conn->close();
?>
