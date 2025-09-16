<?php
$host = "localhost";
$user = "root";
$password = "12345678";
$dbname = "gestion_ventes";

// Connexion MySQLi
$conn = mysqli_connect($host, $user, $password, $dbname);

// Vérifier connexion
if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}
?>
