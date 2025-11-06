<?php
session_start();

// Debug (enlever en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion DB (mysqli)
include 'db.php';

// Vérif session
if (!isset($_SESSION['user_id'])) {
    echo "console.error('Utilisateur non connecté');";
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$delta   = isset($_GET['delta']) ? (int)$_GET['delta'] : -1;

if ($delta <= 0) {
    echo "console.error('Delta invalide (delta=$delta)');";
    exit;
}

// Vérifie si le user existe déjà
$result = $conn->query("SELECT nb_cookie FROM cookie WHERE id_utilisateur = $user_id");

if ($result && $result->num_rows > 0) {
    // Update
    $stmt = $conn->prepare("UPDATE cookie SET nb_cookie = nb_cookie + ? WHERE id_utilisateur = ?");
    $stmt->bind_param("ii", $delta, $user_id);
    $stmt->execute();
} else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO cookie (id_utilisateur, nb_cookie,nb_auto_clicker) VALUES (?, ?, ?)");
    $stmt->bind_param("ii", $user_id, $delta,0);
    $stmt->execute();
}


