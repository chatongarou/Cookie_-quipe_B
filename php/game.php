<?php
session_start();
include 'db.php';

if (empty($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$result = $conn->query("SELECT nb_cookie, nb_auto_clicker FROM cookie WHERE id_utilisateur = $user_id");
$row = $result->fetch_assoc();

$totalCookies = $row['nb_cookie'] ?? 0;
$totalAutoClicker = $row['nb_auto_clicker'] ?? 0;
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cookie Clicker</title>
    <link rel="stylesheet" href="../assets/css/style_accueil.css">
</head>
<body>

<div class="container">
    <main class="main-area">
        <img id="cookie" src="../assets/img/cookie_image.png" alt="cookie">
        <p>Clique le cookie</p>
        <p>Score : <span id="scoreDisplay"><?= $totalCookies ?></span></p>
    </main>

    <aside class="sidebar">
        <h2>Panneau de contrôle</h2>
        <hr >
        <button id="buy_AutoClicker">
            Acheter un Auto-Clicker <br>
            (<span id="dsp_prix_autoclicker"></span> cookies)
        </button>

        <span>Nombre d'Auto-Clicker : 
            <span id="autoClickerDSP"><?= $totalAutoClicker ?></span>
        </span>
        <hr>
        <form action="/Cookie_-quipe_B/php/logout.php" method="post" style="display:inline;">
        <button type="submit">Déconnexion</button>
</form>

    </aside>
</div>


<script src="../assets/js/script.js"></script>

</body>
</html>
