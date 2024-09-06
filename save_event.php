<?php
include 'conn_sql.php';

$race_id = $_POST['race_id'];
$event_type = $_POST['event_type'];
$time_remaining = $_POST['time_remaining'];
$relay_id = $_POST['relay_id'];  // Inclure relay_id pour chaque événement
$timestamp = date('Y-m-d H:i:s');

// Enregistrer l'événement dans la base de données
$sql = "INSERT INTO race_events (race_id, event_type, time_remaining, timestamp, relay_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssi", $race_id, $event_type, $time_remaining, $timestamp, $relay_id);
$stmt->execute();
$stmt->close();
$conn->close();
?>