<?php
require_once '../config/database.php';
redirectIfNotAdmin();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ProAthlete</title>
    <link rel="stylesheet" href="/ecommerce-proathlete/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/ecommerce-proathlete/client/index.php" class="logo">
                <span class="logo-text">ProAthlete Admin</span>
            </a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php">Produits</a></li>
                <li><a href="categories.php">Catégories</a></li>
                <li><a href="orders.php">Commandes</a></li>
                <li><a href="/ecommerce-proathlete/client/index.php">Site Client</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="admin-container">
        <h1>Tableau de Bord Admin</h1>
        
        <?php
        // Get statistics
        $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'en attente'")->fetchColumn();
        $totalRevenue = $pdo->query("
            SELECT SUM(p.price * o.quantity) 
            FROM orders o 
            JOIN products p ON o.product_id = p.id
            WHERE o.status != 'en attente'
        ")->fetchColumn() ?? 0;
        ?>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin: 3rem 0;">
            <div style="background-color: #f5f5f5; padding: 2rem; border-radius: 8px; text-align: center;">
                <h2><?php echo $totalProducts; ?></h2>
                <p>Produits</p>
            </div>
            <div style="background-color: #f5f5f5; padding: 2rem; border-radius: 8px; text-align: center;">
                <h2><?php echo $totalOrders; ?></h2>
                <p>Commandes</p>
            </div>
            <div style="background-color: #f5f5f5; padding: 2rem; border-radius: 8px; text-align: center;">
                <h2><?php echo $pendingOrders; ?></h2>
                <p>En attente</p>
            </div>
            <div style="background-color: #f5f5f5; padding: 2rem; border-radius: 8px; text-align: center;">
                <h2><?php echo number_format($totalRevenue, 2); ?> €</h2>
                <p>Chiffre d'affaires</p>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 3rem;">
            <div>
                <h2>Dernières Commandes</h2>
                <?php
                $stmt = $pdo->query("
                    SELECT o.*, p.name as product_name 
                    FROM orders o 
                    JOIN products p ON o.product_id = p.id 
                    ORDER BY o.order_date DESC 
                    LIMIT 5
                ");
                $recentOrders = $stmt->fetchAll();
                ?>
                
                <?php if (count($recentOrders) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Produit</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <span style="padding: 0.25rem 0.5rem; background-color: 
                                        <?php echo $order['status'] == 'en attente' ? '#fff3cd' : 
                                               ($order['status'] == 'traité' ? '#d1ecf1' : '#d4edda'); ?>; 
                                        border-radius: 4px;">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune commande récente</p>
                <?php endif; ?>
                <a href="orders.php" class="btn">Voir toutes les commandes</a>
            </div>
            
            <div>
                <h2>Produits Faible Stock</h2>
                <?php
                $stmt = $pdo->query("SELECT * FROM products WHERE stock < 10 ORDER BY stock ASC LIMIT 5");
                $lowStockProducts = $stmt->fetchAll();
                ?>
                
                <?php if (count($lowStockProducts) > 0): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockProducts as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo ucfirst($product['category']); ?></td>
                                <td>
                                    <span style="color: <?php echo $product['stock'] < 5 ? '#dc3545' : '#ffc107'; ?>;">
                                        <?php echo $product['stock']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Tous les produits ont un stock suffisant</p>
                <?php endif; ?>
                <a href="products.php" class="btn">Gérer les produits</a>
            </div>
        </div>
        
        <div style="margin-top: 3rem;">
            <h2>Actions Rapides</h2>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <a href="add_product.php" class="btn">Ajouter un produit</a>
                <a href="orders.php" class="btn btn-secondary">Gérer les commandes</a>
                <a href="/ecommerce-proathlete/client/index.php" class="btn btn-secondary">Voir le site</a>
            </div>
        </div>
    </div>
</body>
</html>