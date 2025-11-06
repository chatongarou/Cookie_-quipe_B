<?php
session_start();

// Debug (à enlever en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion DB
include 'db.php';

// Vérif session
if (!isset($_SESSION['user_id'])) {
    echo "console.error('Utilisateur non connecté');";
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$amount  = isset($_GET['amount']) ? (int)$_GET['amount'] : -1;

if ($amount <= 0) {
    echo "console.error('Paramètre auto-clicker invalide (amount=$amount)');";
    exit;
}

// Vérifie si une ligne existe déjà
$result = $conn->query("SELECT nb_auto_clicker FROM cookie WHERE id_utilisateur = $user_id");

if ($result && $result->num_rows > 0) {

    // ✅ Mise à jour de nb_auto_clicker
    $stmt = $conn->prepare("UPDATE cookie SET nb_auto_clicker = nb_auto_clicker + ? WHERE id_utilisateur = ?");
    $stmt->bind_param("ii", $amount, $user_id);
    $stmt->execute();

} else {

    // ✅ Création de la ligne si elle n'existe pas
    $stmt = $conn->prepare("INSERT INTO cookie (id_utilisateur, nb_cookie, nb_auto_clicker) VALUES (?, ?, ?)");
    $zero = 0;
    $stmt->bind_param("iii", $user_id, $zero, $amount);
    $stmt->execute();
}

// Récupère la valeur mise à jour
$result = $conn->query("SELECT nb_auto_clicker FROM cookie WHERE id_utilisateur = $user_id");
$totalAuto = $result->fetch_assoc()['nb_auto_clicker'];

// ✅ Retour dynamique JS
echo "console.log('✅ Auto-clicker +$amount (Total = $totalAuto)');";
