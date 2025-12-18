<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'] ?? '';
    $quantity = $_POST['quantity'];
    
    try {
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_email, customer_address, customer_phone, quantity) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$product_id, $name, $email, $address, $phone, $quantity]);
        
        // Update stock
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);
        
        $success = true;
    } catch (Exception $e) {
        $error = "Erreur lors de la commande : " . $e->getMessage();
    }
}

require_once '../layouts/header.php';
?>

<div class="container" style="min-height: 50vh; display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; max-width: 600px;">
        <?php if (isset($success) && $success): ?>
            <h1 style="color: #000; margin-bottom: 1rem;">Commande réussie !</h1>
            <p style="color: #666; margin-bottom: 2rem;">Merci pour votre commande. Nous vous contacterons bientôt pour confirmer votre achat.</p>
            <a href="products.php" class="btn">Retour aux produits</a>
        <?php elseif (isset($error)): ?>
            <h1 style="color: #d00; margin-bottom: 1rem;">Erreur</h1>
            <p style="color: #666; margin-bottom: 2rem;"><?php echo $error; ?></p>
            <a href="products.php" class="btn">Retour aux produits</a>
        <?php else: ?>
            <h1 style="color: #000; margin-bottom: 1rem;">Formulaire de commande</h1>
            <p style="color: #666; margin-bottom: 2rem;">Veuillez remplir le formulaire pour passer votre commande.</p>
        <?php endif; ?>
    </div>
</div>

<?php
require_once '../layouts/footer.php';
?>