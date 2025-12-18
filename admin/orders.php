<?php
require_once '../config/database.php';
redirectIfNotAdmin();

// Handle status update
if (isset($_GET['update_status'])) {
    $order_id = $_GET['order_id'];
    $status = $_GET['status'];
    $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $order_id]);
    header('Location: orders.php?updated=1');
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$id]);
    header('Location: orders.php?deleted=1');
    exit();
}

$stmt = $pdo->query("
    SELECT o.*, p.name as product_name, p.price 
    FROM orders o 
    JOIN products p ON o.product_id = p.id 
    ORDER BY o.order_date DESC
");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - ProAthlete</title>
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
        <h1>Gestion des Commandes</h1>
        
        <?php if (isset($_GET['updated'])): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                Statut mis à jour avec succès
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                Commande supprimée avec succès
            </div>
        <?php endif; ?>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                        <small><?php echo htmlspecialchars($order['customer_email']); ?></small><br>
                        <small><?php echo htmlspecialchars(substr($order['customer_address'], 0, 30)); ?>...</small>
                    </td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo number_format($order['price'] * $order['quantity'], 2); ?> €</td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                    <td>
                        <form method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" style="padding: 0.25rem;">
                                <option value="en attente" <?php echo $order['status'] == 'en attente' ? 'selected' : ''; ?>>En attente</option>
                                <option value="traité" <?php echo $order['status'] == 'traité' ? 'selected' : ''; ?>>Traité</option>
                                <option value="expédié" <?php echo $order['status'] == 'expédié' ? 'selected' : ''; ?>>Expédié</option>
                            </select>
                            <button type="submit" name="update_status" class="btn" 
                                    style="padding: 0.25rem 0.5rem; font-size: 0.9rem;">
                                Mettre à jour
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="?delete=<?php echo $order['id']; ?>" 
                               class="btn" 
                               style="padding: 0.25rem 0.5rem; font-size: 0.9rem; background-color: #dc3545;"
                               onclick="return confirm('Supprimer cette commande ?')">
                                Supprimer
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>