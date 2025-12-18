<?php
require_once '../config/database.php';
redirectIfNotAdmin();

$message = '';

// Ajouter une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            $message = '<div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">Catégorie ajoutée avec succès!</div>';
        } catch (Exception $e) {
            $message = '<div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">Erreur: Cette catégorie existe déjà!</div>';
        }
    }
}

// Supprimer une catégorie
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        // Vérifier si des produits utilisent cette catégorie
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch();
        
        if ($category) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE category = ?");
            $stmt->execute([$category['name']]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                $message = '<div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 15px;">Impossible de supprimer : ' . $result['count'] . ' produit(s) utilisent cette catégorie</div>';
            } else {
                $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
                $stmt->execute([$id]);
                $message = '<div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">Catégorie supprimée avec succès!</div>';
            }
        }
    } catch (Exception $e) {
        $message = '<div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">Erreur: ' . $e->getMessage() . '</div>';
    }
}

// Récupérer toutes les catégories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories - ProAthlete</title>
    <link rel="stylesheet" href="/ecommerce-proathlete/assets/css/style.css">
</head>
<body>
    <?php require_once '../layouts/header.php'; ?>
    
    <div class="admin-container">
        <h1>Gestion des Catégories</h1>
        
        <?php echo $message; ?>
        
        <!-- Formulaire pour ajouter une catégorie -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h2 style="margin-top: 0;">Ajouter une nouvelle catégorie</h2>
            <form method="POST" style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="name">Nom de la catégorie</label>
                    <input type="text" id="name" name="name" class="form-control" required 
                           placeholder="Ex: Accessoires, Chaussures, Équipement...">
                </div>
                <button type="submit" name="add_category" class="btn">Ajouter la catégorie</button>
            </form>
        </div>
        
        <!-- Liste des catégories existantes -->
        <h2>Catégories existantes</h2>
        
        <?php if (empty($categories)): ?>
            <p style="color: #666; font-style: italic;">Aucune catégorie trouvée.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($category['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($category['description'] ?? '-'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($category['created_at'])); ?></td>
                        <td>
                            <a href="?delete=<?php echo $category['id']; ?>" 
                               class="btn" 
                               style="background-color: #dc3545; padding: 5px 10px; font-size: 0.9rem;"
                               onclick="return confirm('Supprimer cette catégorie ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div style="margin-top: 30px;">
            <a href="products.php" class="btn btn-secondary">← Retour aux produits</a>
        </div>
    </div>
    
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>