<?php
require_once '../layouts/header.php';

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$all_categories = $stmt->fetchAll();

// Handle category filter
$category = $_GET['category'] ?? 'all';
$sql = "SELECT * FROM products";
if (in_array($category, ['homme', 'femme', 'enfant'])) {
    $sql .= " WHERE category = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category]);
} else {
    $stmt = $pdo->query($sql);
}
$products = $stmt->fetchAll();
?>

<section class="products-section">
    <div class="container">
        <h1 style="margin: 2rem 0;">Nos Produits</h1>
        
        <!-- Category Filter -->
        <div style="margin-bottom: 2rem;">
            <a href="?category=all" class="btn <?php echo $category == 'all' ? '' : 'btn-secondary'; ?>">
        Tous
    </a>
    
    <?php foreach ($all_categories as $cat): ?>
    <a href="?category=<?php echo urlencode($cat['name']); ?>" 
       class="btn <?php echo $category == $cat['name'] ? '' : 'btn-secondary'; ?>">
        <?php echo htmlspecialchars($cat['name']); ?>
    </a>
    <?php endforeach; ?>

        </div>
        <?php
if ($category !== 'all') {
    $sql = "SELECT * FROM products WHERE category = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category]);
    $products = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
}
?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if ($product['image'] && $product['image'] !== 'default.jpg'): ?>
                       <img src="../assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="width: 100%; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-tshirt" style="font-size: 3rem; color: #ccc;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="price"><?php echo number_format($product['price'], 2); ?> €</div>
                    <p><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                    <span class="category"><?php echo ucfirst($product['category']); ?></span>
                    <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Commander</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once '../layouts/footer.php';
?>