<?php
// Démarre la session pour récupérer les informations de l'utilisateur
session_start();

// Inclusion du fichier de connexion à la base de données
include 'db.php';

// Vérifie si l'utilisateur est connecté (si user_id est défini dans la session)
if (empty($_SESSION['user_id'])) {
    // Si non connecté, redirection vers la page d'inscription ou login
    header("Location: register.php");
    exit();
}

// Récupère l'ID utilisateur depuis la session et s'assure que c'est un entier
$user_id = (int)$_SESSION['user_id'];

// Exécute une requête pour récupérer le nombre de cookies et d'auto-clickers pour cet utilisateur
$result = $conn->query("SELECT nb_cookie, nb_auto_clicker FROM cookie WHERE id_utilisateur = $user_id");

// Récupère la première ligne de résultat (il ne doit y en avoir qu'une)
$row = $result->fetch_assoc();

// Utilise l'opérateur null coalescing pour éviter les valeurs nulles
// Si la valeur n'existe pas, on met 0 par défaut
$totalCookies = $row['nb_cookie'] ?? 0;
$totalAutoClicker = $row['nb_auto_clicker'] ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cookie Clicker</title>
    <!-- Lien vers le fichier CSS pour le style -->
    <link rel="stylesheet" href="../assets/css/style_accueil.css">
</head>
<body>

<div class="container">
    <!-- Zone principale du jeu -->
    <main class="main-area">
        <!-- Image du cookie cliquable -->
        <img id="cookie" src="../assets/img/cookie_image.png" alt="cookie">
        <p>Clique le cookie</p>
        <!-- Affiche le score initial récupéré depuis la BDD -->
        <p>Score : <span id="scoreDisplay"><?= $totalCookies ?></span></p>
    </main>

    <!-- Barre latérale avec options et informations -->
    <aside class="sidebar">
        <h2>Panneau de contrôle</h2>
        <hr>
        <!-- Bouton pour acheter un auto-clicker -->
        <button id="buy_AutoClicker">
            Acheter un Auto-Clicker <br>
            (<span id="dsp_prix_autoclicker"></span> cookies)
        </button>

        <!-- Affiche le nombre d'auto-clickers possédés -->
        <span>Nombre d'Auto-Clicker : 
            <span id="autoClickerDSP"><?= $totalAutoClicker ?></span>
        </span>
        <hr>
        <!-- Formulaire pour se déconnecter -->
        <form action="/Cookie_-quipe_B/php/logout.php" method="post" style="display:inline;">
            <button type="submit">Déconnexion</button>
        </form>
    </aside>
</div>

<!-- Inclusion du fichier JavaScript pour gérer le jeu (clics, auto-clickers, score...) -->
<script src="../assets/js/script.js"></script>

</body>
</html>
