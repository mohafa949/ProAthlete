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
<body></body>
<link href="/ecommerce-proathlete/assets/css/index.css" rel="stylesheet">
<header id="main-header">
            <nav>
                 <a  href="/ecommerce-proathlete/client/index.php"><img width="200px" src="/projet-ecommerce/assets/images/logo-proathlete.png"></a>
                
                <span class="logo-text">ProAthlete</span>
            </a>
            <ul class="nav-links">
                <li><a style="color : black;" href="/ecommerce-proathlete/client/index.php">Accueil</a></li>
                <li><a style="color : black;" href="/ecommerce-proathlete/client/products.php">Produits</a></li>
                <li><a style="color : black;" href="/ecommerce-proathlete/client/about.php">À Propos</a></li>
                <?php if (isAdminLoggedIn()): ?>
                    <li><a style="color : black;" href="/ecommerce-proathlete/admin/dashboard.php">Admin</a></li>
                    <li><a style="color : black;" href="/ecommerce-proathlete/admin/logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a style="color : black;" href="/ecommerce-proathlete/admin/login.php">Admin</a></li>
                <?php endif; ?>
            </ul>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
            </nav>
            

        </header>
<section class="hero">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="/ecommerce-proathlete/assets/videos/hero.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="hero-content">
            <h1 style="color : white;">Perform Like a Pro</h1>
            <p style="color : white;">Découvrez notre nouvelle collection d'articles de sport</p>
            <a href="/ecommerce-proathlete/client/products.php" class="btn">Acheter maintenant</a>
        </div>
    </section>

    <section class="categories">
        <h1>Que recherches-tu ?</h1>
        <div class="parent">
            <div class="categorie">
                <a href="/ecommerce-proathlete/client/products.php?category=homme"><img class="image" width="100%" src="/projet-ecommerce/assets/images/homme.jpg"></a>
                <span class="label">Hommes</span>
            </div>
            <div class="categorie">
                <a href="/ecommerce-proathlete/client/products.php?category=femme"><img class="image" width="100%" src="/projet-ecommerce/assets/images/femme.jpg"></a>
                <span class="label">Femmes</span>
            </div>
            <div class="categorie">
                <a href="/ecommerce-proathlete/client/products.php?category=enfant"><img class="image" width="100%" src="/projet-ecommerce/assets/images/enfants.jpg"></a>
                <span class="label">Enfants</span>
            </div>
        </div>
        <h1>DÉCOUVRE LES NOUVEAUTÉS</h1>
        <div class="parent">
            <div class="sport">
                <a><img class="image" width="100%" src="/projet-ecommerce/assets/images/running_fixed.jpg"></a>
                <span class="label">Running</span>
            </div>
            <div class="sport">
                <a><img class="image" width="100%" src="/projet-ecommerce/assets/images/football_fixed.jpg"></a>
                <span class="label">Football</span>
            </div>
            <div class="sport">
                <a><img class="image" width="100%" src="/projet-ecommerce/assets/images/boxing.jpg"></a>
                <span class="label">Boxing</span>
            </div>
        </div>

       
    </section>
    

<section class="featured-products">
    <div class="container">
        <h2 style="text-align: center; margin: 3rem 0;">Produits Populaires</h2>
        <div class="products-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM products LIMIT 4");
            while ($product = $stmt->fetch()):
            ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if ($product['image'] && $product['image'] !== 'default.jpg'): ?>
                        <img src="/ecommerce-proathlete/assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
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
                    <span class="category"><?php echo ucfirst($product['category']); ?></span>
                    <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn" style="margin-top: 1rem; display: block; text-align: center;">Voir Détails</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>


<h1>Collections spéciales</h1>
    <section id="celebrity-collections" class="celebrity-fullscreen">
        
        <div class="slideshow" id="slideshow"></div>
    </section>

 <h1>Avis de nos clients</h1>
   <section class="avis-clients">
    <div class="slider-avis">
        <div class="avis active">
        <p>“Excellente qualité et livraison rapide. J’adore mes nouveaux gants de boxe !”</p>
        <div class="stars">★★★★★</div>
        <h4>- Ahmed B.</h4>
        </div>

        <div class="avis">
        <p>“Les meilleures chaussures de sport à prix raisonnable. Service client au top !”</p>
        <div class="stars">★★★★☆</div>
        <h4>- Lina K.</h4>
        </div>

        <div class="avis">
        <p>“Produits variés, site clair et facile à utiliser. Je recommande vivement !”</p>
        <div class="stars">★★★★★</div>
        <h4>- Karim D.</h4>
        </div>

        <div class="avis">
        <p>“Toujours satisfait de mes commandes. Qualité et fiabilité au rendez-vous.”</p>
        <div class="stars">★★★★☆</div>
        <h4>- Sofia M.</h4>
        </div>

        
  </div>
    </section>

 <section class="brands">
  <div class="brand-track">
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/nike.png" alt="Nike"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/adidas.png" alt="Adidas"></div>
    <div class="brand-slide"><img width="3000px" src="/projet-ecommerce/assets/images/pumaa.png" alt="Puma"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/venum.png" alt="Venum"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/thenorthface.png" alt="The North Face"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/reebok.png" alt="reebok"></div>

    <!-- duplicate for seamless loop -->
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/nike.png" alt="Nike"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/adidas.png" alt="Adidas"></div>
    <div class="brand-slide"><img width="3000px" src="/projet-ecommerce/assets/images/pumaa.png" alt="Puma"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/venum.png" alt="Venum"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/thenorthface.png" alt="The North Face"></div>
    <div class="brand-slide"><img src="/projet-ecommerce/assets/images/reebok.png" alt="reebok"></div>
    
  </div>
</section>
    <script>
const avis = document.querySelectorAll('.avis');
let currentAvis = 0;

function nextAvis() {
  avis[currentAvis].classList.remove('active');
  currentAvis = (currentAvis + 1) % avis.length;
  avis[currentAvis].classList.add('active');
}

setInterval(nextAvis, 4000);
</script>

<script src="/ecommerce-proathlete/assets/js/celebrity.js"></script>
<?php
require_once '../layouts/footer.php';
?>