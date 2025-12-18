<?php
// Script pour créer un administrateur avec mot de passe crypté
$password = 'admin123'; // Mot de passe que vous voulez
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe original: " . $password . "\n";
echo "Mot de passe crypté: " . $hashedPassword . "\n\n";

// Requête SQL à exécuter
echo "Exécutez cette requête SQL dans phpMyAdmin ou MySQL :\n\n";
echo "INSERT INTO admins (email, password, username) VALUES ";
echo "('admin@proathlete.com', '" . $hashedPassword . "', 'Administrateur', 'admin');";
?>