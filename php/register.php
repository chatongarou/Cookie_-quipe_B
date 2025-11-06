<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// register.php
session_start();
include 'db.php'; // doit définir $conn (mysqli)

// Variables pour affichage
$errors = [];
$success = false;

// Traitement du POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation basique
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

    if ($username === '') {
        $errors[] = "Le pseudo est requis.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Le pseudo doit contenir au moins 3 caractères.";
    }

    if ($password === '') {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
        // Vérifier si le pseudo existe déjà
        $stmt = $conn->prepare("SELECT id FROM utilisateur WHERE pseudo = ?");
        if ($stmt === false) {
            $errors[] = "Erreur serveur (prepare) : " . $conn->error;
        } else {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = "Ce pseudo est déjà pris.";
                $stmt->close();
            } else {
                $stmt->close();
                // Insérer l'utilisateur
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $ins = $conn->prepare("INSERT INTO utilisateur (pseudo, mdp) VALUES (?, ?)");
                if ($ins === false) {
                    $errors[] = "Erreur serveur (prepare insert) : " . $conn->error;
                } else {
                    $ins->bind_param('ss', $username, $hashed);
                    if ($ins->execute()) {
                        $success = true;
                        // Optionnel : récupérer l'id et loguer automatiquement
                        $new_id = $ins->insert_id;
                        $_SESSION['user_id'] = $new_id; // décommente si tu veux loguer directement
                        // rediriger vers login ou game
                        header('Location: game.php');
                        exit();
                    } else {
                        $errors[] = "Erreur lors de l'inscription : " . $ins->error;
                    }
                    $ins->close();
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Inscription</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;padding:20px}
    form{max-width:400px}
    label{display:block;margin-top:10px}
    input{width:100%;padding:8px;margin-top:4px}
    .errors{background:#ffe6e6;padding:10px;border:1px solid #ffb3b3}
    .success{background:#e6ffe6;padding:10px;border:1px solid #b3ffb3}
  </style>
  <link rel="stylesheet" href="../assets/css/style_login.css">

</head>
<body>
  <h1>Inscription</h1>

  <?php if (!empty($errors)): ?>
    <div class="errors">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success">Inscription réussie. Redirection en cours...</div>
  <?php endif; ?>

  <form method="post" action="register.php" autocomplete="off">
    <label for="username">Pseudo</label>
    <input id="username" name="username" type="text" required minlength="3" pattern=".{3,}" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">

    <label for="password">Mot de passe</label>
    <input id="password" name="password" type="password" required minlength="6">

    <label for="password_confirm">Confirmer le mot de passe</label>
    <input id="password_confirm" name="password_confirm" type="password" required minlength="6">

    <button type="submit" style="margin-top:12px;padding:10px 16px">S'inscrire</button>
  </form>

  <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
</body>
</html>



