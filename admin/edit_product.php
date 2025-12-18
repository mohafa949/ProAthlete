<?php
require_once '../config/database.php';
redirectIfNotAdmin();


$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();

$product = null;
$message = '';

// Récupérer l'ID du produit à modifier
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header('Location: products.php?error=Produit non trouvé');
        exit();
    }
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = (int)$_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    
    try {
        // Mise à jour des informations de base
        $stmt = $pdo->prepare("UPDATE products SET 
                              name = ?, 
                              description = ?, 
                              price = ?, 
                              category = ?, 
                              stock = ?
                              WHERE id = ?");
        $stmt->execute([$name, $description, $price, $category, $stock, $product_id]);
        
        // Gestion de l'upload de nouvelle image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../assets/images/products/';
            
            // Créer le dossier s'il n'existe pas
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Générer un nom de fichier unique
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;
            
            // Vérifier le type de fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['image']['type'];
            
            // Vérifier l'extension
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileType, $allowedTypes) || in_array($fileExt, $allowedExt)) {
                // Vérifier la taille (max 2MB)
                if ($_FILES['image']['size'] <= 2000000) {
                    // Supprimer l'ancienne image si elle n'est pas default.jpg
                    if ($product['image'] !== 'default.jpg' && $product['image'] !== '') {
                        $oldImagePath = $uploadDir . $product['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    // Déplacer la nouvelle image
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        // Mettre à jour le nom de l'image dans la base
                        $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                        $stmt->execute([$fileName, $product_id]);
                        $message = '<div class="success">Produit et image modifiés avec succès!</div>';
                    } else {
                        $message = '<div class="error">Erreur lors du déplacement de l\'image.</div>';
                    }
                } else {
                    $message = '<div class="error">L\'image est trop volumineuse (max 2MB).</div>';
                }
            } else {
                $message = '<div class="error">Format d\'image non supporté. Formats acceptés: JPG, PNG, GIF, WebP</div>';
            }
        } else {
            // Si pas de nouvelle image, on garde l'ancienne
            $message = '<div class="success">Produit modifié avec succès (image inchangée)!</div>';
        }
        
        // Recharger les données du produit
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
    } catch (Exception $e) {
        $message = '<div class="error">Erreur: ' . $e->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit - ProAthlete</title>
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
        
        .current-image {
            max-width: 200px;
            max-height: 200px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            object-fit: cover;
        }
        
        .image-container {
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Modifier le Produit</h1>
            <a href="products.php" class="btn btn-secondary">← Retour aux produits</a>
        </div>
        
        <?php if ($message): ?>
            <?php echo $message; ?>
        <?php endif; ?>
        
        <?php if ($product): ?>
        <form method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="update_product" value="1">
            
            <div class="form-group">
                <label for="name">Nom du produit *</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Prix (€) *</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" 
                       value="<?php echo $product['price']; ?>" required>
            </div>
            
            <div class="form-group">
    <label for="category">Catégorie *</label>
    <select id="category" name="category" class="form-control" required>
        <option value="">-- Sélectionner une catégorie --</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?php echo htmlspecialchars($cat['name']); ?>"
                <?php echo $product['category'] == $cat['name'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($cat['name']); ?>
        </option>
        <?php endforeach; ?>
    </select>
    <small>
        <a href="categories.php" style="color: #666; text-decoration: none;">
            + Gérer les catégories
        </a>
    </small>
</div>
            
            <div class="form-group">
                <label for="stock">Stock *</label>
                <input type="number" id="stock" name="stock" class="form-control" min="0" 
                       value="<?php echo $product['stock']; ?>" required>
            </div>
            
            <div class="image-container">
                <label>Image actuelle</label>
                <div>
                    <?php 
                    $imagePath = '../../assets/images/products/' . $product['image'];
                    if (file_exists($imagePath) && $product['image'] !== 'default.jpg' && $product['image'] !== ''): 
                    ?>
                        <img src="../../assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="current-image">
                        <p><small>Fichier: <?php echo $product['image']; ?></small></p>
                    <?php else: ?>
                        <div style="width: 200px; height: 150px; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                            <span style="color: #999;">Image par défaut</span>
                        </div>
                        <p><small>Aucune image spécifique</small></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Changer l'image (optionnel)</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                <small>Laissez vide pour garder l'image actuelle. Formats: JPG, PNG, GIF, WebP (max 2MB)</small>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn">Enregistrer les modifications</button>
                <a href="products.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
        
        <!-- Section de débogage (à enlever en production) -->
        <div style="margin-top: 3rem; padding: 1rem; background-color: #f8f9fa; border-radius: 4px;">
            <h3>Informations de débogage :</h3>
            <ul style="color: #666;">
                <li>ID produit: <?php echo $product['id']; ?></li>
                <li>Image actuelle: <?php echo $product['image']; ?></li>
                <li>Chemin image: <?php echo realpath('../../assets/images/products/' . $product['image']); ?></li>
                <li>Fichier existe: <?php echo file_exists('../../assets/images/products/' . $product['image']) ? 'Oui' : 'Non'; ?></li>
                <li>Dossier upload: <?php echo realpath('../../assets/images/products/'); ?></li>
                <li>Dossier existe: <?php echo is_dir('../../assets/images/products/') ? 'Oui' : 'Non'; ?></li>
                <li>PHP peut écrire: <?php echo is_writable('../../assets/images/products/') ? 'Oui' : 'Non'; ?></li>
            </ul>
        </div>
        
        <?php else: ?>
        <div style="text-align: center; padding: 40px;">
            <p style="color: #666; font-size: 1.2rem;">Produit non trouvé.</p>
            <a href="products.php" class="btn">Retour à la liste des produits</a>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Vérification de la taille du fichier avant upload
        document.getElementById('image').addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var fileSize = file.size / 1024 / 1024; // en MB
                if (fileSize > 2) {
                    alert('Le fichier est trop volumineux (max 2MB).');
                    e.target.value = '';
                }
                
                // Vérifier l'extension
                var fileName = file.name.toLowerCase();
                var validExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
                var hasValidExtension = validExtensions.some(function(ext) {
                    return fileName.endsWith(ext);
                });
                
                if (!hasValidExtension) {
                    alert('Format de fichier non supporté. Formats acceptés: JPG, PNG, GIF, WebP');
                    e.target.value = '';
                }
            }
        });
    </script>
</body>
</html>