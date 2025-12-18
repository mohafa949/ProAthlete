<?php
// Script pour vérifier les permissions
echo "Vérification des permissions...<br>";

$imagesDir = __DIR__ . '/ecommerce-proathlete/assets/images/products/';

// Vérifier si le dossier existe
if (!is_dir($imagesDir)) {
    echo "Le dossier n'existe pas. Tentative de création...<br>";
    if (mkdir($imagesDir, 0755, true)) {
        echo "✓ Dossier créé avec succès<br>";
    } else {
        echo "✗ Erreur lors de la création du dossier<br>";
    }
} else {
    echo "✓ Dossier existe<br>";
}

// Vérifier les permissions
echo "Chemin du dossier: " . realpath($imagesDir) . "<br>";
echo "Permissions: " . substr(sprintf('%o', fileperms($imagesDir)), -4) . "<br>";

// Vérifier si PHP peut écrire
$testFile = $imagesDir . 'test.txt';
if (file_put_contents($testFile, 'test')) {
    echo "✓ PHP peut écrire dans le dossier<br>";
    unlink($testFile);
} else {
    echo "✗ PHP NE peut PAS écrire dans le dossier<br>";
}

// Vérifier la configuration PHP
echo "<br>Configuration PHP:<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "file_uploads: " . ini_get('file_uploads') . "<br>";

// Vérifier le dossier temporaire
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "<br>";
?>