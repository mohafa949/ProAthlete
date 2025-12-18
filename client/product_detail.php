<?php
require_once '../layouts/header.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit();
}
?>

<section class="product-detail">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start; margin: 4rem 0;">
            <!-- Product Image -->
            <div>
                <?php if ($product['image'] && $product['image'] !== 'default.jpg'): ?>
                    <img src="/ecommerce-proathlete/assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         style="width: 100%; height: 400px; object-fit: cover;">
                <?php else: ?>
                    <div style="width: 100%; height: 400px; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-tshirt" style="font-size: 8rem; color: #ccc;"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Product Info -->
            <div>
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="price" style="font-size: 2rem; margin: 1rem 0;"><?php echo number_format($product['price'], 2); ?> €</div>
                <span class="category"><?php echo ucfirst($product['category']); ?></span>
                
                <div style="margin: 2rem 0;">
                    <h3>Description</h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                </div>
                
                <div style="margin: 2rem 0;">
                    <h3>Stock disponible : <?php echo $product['stock']; ?></h3>
                </div>
                
                <!-- Order Form -->
                <form action="submit_order.php" method="POST" style="margin-top: 2rem;">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Nom complet *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Adresse complète *</label>
                        <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantité *</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" max="<?php echo $product['stock']; ?>" value="1" required>
                    </div>
                    
                    <button type="submit" class="btn">Passer la commande</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
require_once '../layouts/footer.php';
?>