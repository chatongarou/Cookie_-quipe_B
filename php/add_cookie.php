<?php
session_start();
header('Content-Type: application/json');
include '../php/db.php'; // <-- ton fichier de connexion PDO

// Vérifie que l'utilisateur est bien connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['delta']) || !is_numeric($data['delta'])) {
    echo json_encode(['success' => false, 'error' => 'Paramètre delta manquant']);
    exit;
}

$delta = (int)$data['delta'];
if ($delta <= 0) {
    echo json_encode(['success' => false, 'error' => 'Aucun cookie à ajouter']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Vérifie si l’utilisateur a déjà une ligne dans la table cookie
    $stmt = $pdo->prepare("SELECT nb_cookie FROM cookie WHERE id_utilisateur = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        // Mise à jour du compteur
        $update = $pdo->prepare("UPDATE cookie SET nb_cookie = nb_cookie + ? WHERE id_utilisateur = ?");
        $update->execute([$delta, $user_id]);
    } else {
        // Première insertion pour ce joueur
        $insert = $pdo->prepare("INSERT INTO cookie (id_utilisateur, nb_cookie) VALUES (?, ?)");
        $insert->execute([$user_id, $delta]);
    }

    // Récupération du total actuel
    $stmt = $pdo->prepare("SELECT nb_cookie FROM cookie WHERE id_utilisateur = ?");
    $stmt->execute([$user_id]);
    $total = (int)$stmt->fetchColumn();

    echo json_encode(['success' => true, 'total' => $total]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
