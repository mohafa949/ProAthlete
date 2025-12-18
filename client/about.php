<?php
require_once '../layouts/header.php';
?>

<section class="about-section">
    <div class="container">
        <h1 style="text-align: center; margin: 3rem 0;">À Propos de ProAthlete</h1>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="margin-bottom: 3rem;">
                <h2 style="margin-bottom: 1rem;">Notre Mission</h2>
                <p style="color: #666; line-height: 1.8;">
                    ProAthlete est né de la passion pour le sport et du désir de fournir des équipements 
                    de qualité supérieure à tous les athlètes, qu'ils soient professionnels ou amateurs. 
                    Notre mission est de vous accompagner dans la réalisation de vos performances sportives 
                    grâce à des articles sélectionnés avec soin.
                </p>
            </div>
            
            <div style="margin-bottom: 3rem;">
                <h2 style="margin-bottom: 1rem;">Nos Valeurs</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <div style="padding: 2rem; background-color: #f5f5f5; border-radius: 8px;">
                        <h3 style="margin-bottom: 1rem;">Qualité</h3>
                        <p>Des produits durables et performants</p>
                    </div>
                    <div style="padding: 2rem; background-color: #f5f5f5; border-radius: 8px;">
                        <h3 style="margin-bottom: 1rem;">Accessibilité</h3>
                        <p>Pour hommes, femmes et enfants</p>
                    </div>
                    <div style="padding: 2rem; background-color: #f5f5f5; border-radius: 8px;">
                        <h3 style="margin-bottom: 1rem;">Service</h3>
                        <p>Un accompagnement personnalisé</p>
                    </div>
                </div>
            </div>
            
            <div style="margin-bottom: 3rem;">
                <h2 style="margin-bottom: 1rem;">Notre Histoire</h2>
                <p style="color: #666; line-height: 1.8;">
                    Fondé en 2024, ProAthlete a rapidement établi sa réputation comme une référence 
                    dans le monde des équipements sportifs. Notre équipe est composée d'anciens athlètes 
                    et de passionnés de sport qui comprennent vos besoins et s'engagent à vous offrir 
                    le meilleur.
                </p>
            </div>
            
            <div style="text-align: center; margin: 4rem 0;">
                <h2 style="margin-bottom: 2rem;">Prêt à commencer ?</h2>
                <a href="products.php" class="btn">Découvrir nos produits</a>
            </div>
        </div>
    </div>
</section>

<?php
require_once '../layouts/footer.php';
?>