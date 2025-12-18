<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}

// Redirect if already logged in
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - ProAthlete</title>
    <link rel="stylesheet" href="/ecommerce-proathlete/assets/css/style.css">
</head>
<body style="background-color: #f5f5f5;">
    <div class="container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div style="background-color: white; padding: 3rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
            <h1 style="text-align: center; margin-bottom: 2rem; color: #000;">Connexion Admin</h1>
            
            <?php if (isset($error)): ?>
                <div style="background-color: #fee; color: #c00; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Se connecter</button>
            </form>
            
            <p style="text-align: center; margin-top: 2rem; color: #666;">
                <a href="/ecommerce-proathlete/client/index.php" style="color: #666;">Retour au site</a>
            </p>
        </div>
    </div>
</body>
</html>