<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'cookie_db';
$port = 3307; 

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>
