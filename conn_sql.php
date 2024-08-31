<?php
// Establish the database connection
 $DB_SERVER = 'localhost';
 $DB_USERNAME = 'root';
 $DB_PASSWORD = 'yDgK4fRnsyJnqJPMLaHM7aqkT';
 $DB_NAME = 'pe2dt';
 
 // Connexion à la base de données
 $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 }

?>