Cookie Clicker — README

1. Présentation

Cookie Clicker est un petit jeu web où l’utilisateur peut cliquer sur un cookie pour gagner des points et acheter des auto-clickers qui génèrent automatiquement des cookies. Les scores et le nombre d’auto-clickers sont sauvegardés dans une base de données MySQL.

2. Prérequis

- Windows avec WAMP installé (https://www.wampserver.com/)
- PHP (inclus dans WAMP)
- MySQL (inclus dans WAMP)
- Navigateur web moderne (Chrome, Firefox, Edge…)

3. Installation

3.1 Copier les fichiers

1. Place tout le dossier de ton projet Cookie_-quipe_B dans le dossier :

C:\wamp64\www\

Exemple :

C:\wamp64\www\Cookie_-quipe_B\

2. Le projet doit contenir :

Cookie_-quipe_B/
├── php/
│   ├── db.php
│   ├── add_cookie.php
│   └── add_auto_clicker.php
├── assets/
│   ├── css/style_accueil.css
│   └── js/script.js
├── index.php (ou pageHTML.php)
└── autres fichiers HTML

3.2 Créer la base de données

1. Ouvre phpMyAdmin via WAMP :  
   http://localhost/phpmyadmin/

2. Crée une base de données nommée :

CREATE DATABASE cookie_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

3. Crée la table cookie :

CREATE TABLE cookie (
    id_utilisateur INT PRIMARY KEY,
    nb_cookie INT NOT NULL DEFAULT 0,
    nb_auto_clicker INT NOT NULL DEFAULT 0
);

3.3 Configurer la connexion dans db.php

Vérifie que db.php contient les bons paramètres MySQL :

<?php
$host = '127.0.0.1';
$user = 'root';
$pass = ''; // mot de passe MySQL
$dbname = 'cookie_db';
$port = 3306; // port MySQL par défaut WAMP

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>

Modifie $user, $pass et $port si nécessaire.

4. Lancement du site

1. Lance WAMP et assure-toi que les services Apache et MySQL sont démarrés.  
2. Ouvre ton navigateur et tape :

http://localhost/Cookie_-quipe_B/index.php

Remplace index.php par le nom réel de ton fichier principal (pageHTML.php si tu l’as renommé).

3. Connecte-toi si tu as un système d’authentification (register.php / login).  
4. Clique sur le cookie et achète des auto-clickers. Les données seront sauvegardées en temps réel dans MySQL.

5. Fonctionnalités

- Cliquer sur le cookie : augmente le score.  
- Auto-clickers : génèrent automatiquement des cookies par seconde.  
- Sauvegarde côté serveur : chaque clic ou auto-clicker acheté met à jour la base de données.  
- Affichage dynamique : score et nombre d’auto-clickers affichés en temps réel.  
- Dynamic script loading : les données sont envoyées sans recharger la page.
