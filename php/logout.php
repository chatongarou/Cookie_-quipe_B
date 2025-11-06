<?php
session_start();

// Détruit uniquement l'ID utilisateur
unset($_SESSION['user_id']);

// Ou pour détruire complètement toutes les sessions :
// session_destroy();

header("Location: login.php");
exit();
?>
