<?php
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProAthlete - Articles de Sport</title>
    <link rel="stylesheet" href="/ecommerce-proathlete/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/ecommerce-proathlete/client/index.php" class="logo" style="display: flex; align-items: center; gap: 10px;">
                <img width="80px" src="/ecommerce-proathlete/assets/images/logo_black.jpg">
                <span  class="logo-text">
                    
                ProAthlete</span>
            </a>
            <ul class="nav-links">
                <li><a href="/ecommerce-proathlete/client/index.php">Accueil</a></li>
                <li><a href="/ecommerce-proathlete/client/products.php">Produits</a></li>
                <li><a href="/ecommerce-proathlete/client/about.php">À Propos</a></li>
                <?php if (isAdminLoggedIn()): ?>
                    <li><a href="/ecommerce-proathlete/admin/dashboard.php">Admin</a></li>
                    <li><a href="/ecommerce-proathlete/admin/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/ecommerce-proathlete/admin/login.php">Admin</a></li>
                <?php endif; ?>
            </ul>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>
    <main>