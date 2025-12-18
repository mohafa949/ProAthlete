<?php
// Activer les erreurs temporairement
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
redirectIfNotAdmin();

// Initialiser les variables
$message = '';

// Vérifier si on doit supprimer un produit
if (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        
        // Récupérer le nom de l'image pour la supprimer
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        
        // Supprimer l'image si elle existe et n'est pas default.jpg
        if ($product && $product['image'] !== 'default.jpg') {
            $imagePath = '../assets/images/products/' . $product['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Supprimer le produit de la base de données
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        $message = '<div class="success">Produit supprimé avec succès</div>';
    } catch (Exception $e) {
        $message = '<div class="error">Erreur: ' . $e->getMessage() . '</div>';
    }
}

// Mettre à jour le stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    try {
        $id = (int)$_POST['product_id'];
        $stock = (int)$_POST['stock'];
        
        $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->execute([$stock, $id]);
        
        $message = '<div class="success">Stock mis à jour avec succès</div>';
    } catch (Exception $e) {
        $message = '<div class="error">Erreur: ' . $e->getMessage() . '</div>';
    }
}

// Récupérer tous les produits
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $message = '<div class="error">Erreur de base de données: ' . $e->getMessage() . '</div>';
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - ProAthlete</title>
    <link rel="stylesheet" href="/ecommerce-proathlete/assets/css/style.css">
    <style>
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-direction: column;
            min-width: 120px;
        }
        
        .btn-small {
    padding: 5px 10px;
    font-size: 0.9rem;
    text-align: center;
    display: block;
    width: 100%;
}

/* Couleur pour le bouton Modifier */
.btn-modifier {
    background-color: #28a745;
    color: white;
}

.btn-modifier:hover {
    background-color: #218838;
}
    </style>
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
        <h1>Gestion des Produits</h1>
        
        <?php echo $message; ?>
        
        <div style="margin: 20px 0; display: flex; justify-content: space-between; align-items: center;">
            <p>Total produits: <?php echo count($products); ?></p>
            <a href="add_product.php" class="btn">+ Ajouter un produit</a>
        </div>
        
        <?php if (count($products) > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Catégorie</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td>
                        <?php 
                        $imagePath = '../assets/images/products/' . $product['image'];
                        if (file_exists($imagePath) && $product['image'] !== 'default.jpg'): 
                        ?>
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <?php else: ?>
                            <div style="width: 80px; height: 80px; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                <span style="color: #999;">No image</span>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                        <small><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?>...</small>
                    </td>
                    <td><?php echo number_format($product['price'], 2); ?> €</td>
                    <td>
                        <span style="padding: 3px 8px; background-color: #f0f0f0; border-radius: 3px;">
                            <?php echo ucfirst($product['category']); ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" 
                                   style="width: 70px; padding: 5px;">
                            <button type="submit" name="update_stock" class="btn btn-small">
                                ✓
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/ecommerce-proathlete/client/product_detail.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-small btn-secondary" target="_blank">
                                Voir
                            </a>

                             <a href="edit_product.php?id=<?php echo $product['id']; ?>" 
                                class="btn btn-small" 
                                style="background-color: #28a745;">
                                    Modifier
                                </a>

                            <a href="?delete=<?php echo $product['id']; ?>" 
                               class="btn btn-small" 
                               style="background-color: #dc3545;"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                Supprimer
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div style="text-align: center; padding: 40px; background-color: #f9f9f9; border-radius: 8px;">
            <p style="font-size: 1.2rem; color: #666;">Aucun produit trouvé.</p>
            <a href="add_product.php" class="btn">Ajouter votre premier produit</a>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Confirmation de suppression
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('a[href*="delete"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>