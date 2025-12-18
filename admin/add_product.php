<?php
require_once '../config/database.php';
redirectIfNotAdmin();


$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();


// Définir le chemin absolu
$baseDir = __DIR__ . '/../assets/images/products/';

// Créer le dossier s'il n'existe pas
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    
    // Gestion de l'upload d'image
    $image = 'default.jpg';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $baseDir;
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $fileName;
        
        // Vérifier le type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            // Vérifier la taille (max 2MB)
            if ($_FILES['image']['size'] <= 2000000) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $image = $fileName;
                } else {
                    $uploadError = "Erreur lors de l'upload de l'image.";
                }
            } else {
                $uploadError = "L'image est trop volumineuse (max 2MB).";
            }
        } else {
            $uploadError = "Format d'image non supporté.";
        }
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadError = "Erreur d'upload: " . $_FILES['image']['error'];
    }
    
    if (!isset($uploadError)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image, stock) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category, $image, $stock]);
        
        header('Location: products.php?added=1');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit - ProAthlete</title>
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
        <h1>Ajouter un Nouveau Produit</h1>
        
        <?php if (isset($uploadError)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                <?php echo $uploadError; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" style="max-width: 600px; margin-top: 2rem;">
            <div class="form-group">
                <label for="name">Nom du produit *</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Prix (€) *</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
    <label for="category">Catégorie *</label>
    <select id="category" name="category" class="form-control" required>
        <option value="">-- Sélectionner une catégorie --</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?php echo htmlspecialchars($cat['name']); ?>">
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
                <label for="stock">Stock initial *</label>
                <input type="number" id="stock" name="stock" class="form-control" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="image">Image du produit *</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                <small>Formats acceptés: JPG, PNG, GIF, WebP (max 2MB)</small>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn">Ajouter le produit</button>
                <a href="products.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>