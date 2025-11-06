<?php
session_start();
include 'db.php'; // ton fichier de connexion MySQLi

$errors = [];
$success = false;

// Si l'utilisateur est déjà connecté, on le redirige vers le jeu


// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        $errors[] = "Veuillez remplir tous les champs.";
    } else {
        // Récupération de l'utilisateur
        $stmt = $conn->prepare("SELECT id, mdp FROM utilisateur WHERE pseudo = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();

                // Vérifie le mot de passe
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $id;

                    // Redirection vers le jeu
                    header("Location: game.php");
                    exit();
                } else {
                    $errors[] = "Mot de passe incorrect.";
                }
            } else {
                $errors[] = "Aucun utilisateur trouvé avec ce pseudo.";
            }
            $stmt->close();
        } else {
            $errors[] = "Erreur serveur : " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;padding:20px}
    form{max-width:400px}
    label{display:block;margin-top:10px}
    input{width:100%;padding:8px;margin-top:4px}
    .errors{background:#ffe6e6;padding:10px;border:1px solid #ffb3b3}
  </style>
   <link rel="stylesheet" href="../assets/css/style_login.css">
</head>
<body>
  <h1>Connexion</h1>

  <?php if (!empty($_GET['registered'])): ?>
    <div style="background:#e6ffe6;padding:10px;border:1px solid #b3ffb3">
      ✅ Inscription réussie, vous pouvez maintenant vous connecter.
    </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="errors">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="login.php" autocomplete="off">
    <label for="username">Pseudo</label>
    <input id="username" name="username" type="text" required>

    <label for="password">Mot de passe</label>
    <input id="password" name="password" type="password" required>

    <button type="submit" style="margin-top:12px;padding:10px 16px">Se connecter</button>
  </form>

  <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
</body>
</html>
