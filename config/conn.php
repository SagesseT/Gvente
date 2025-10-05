<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "gfactures";

// Connexion MySQLi
$conn = mysqli_connect($host, $user, $password, $dbname);

// Vérifier connexion
if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}
?>
