<?php
session_start();
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Test avec utilisateur simple
    if($username == "sarahj" && $password == "password123") {
        $_SESSION["patient_id"] = 1;
        $_SESSION["patient_name"] = "Sarah Johnson";
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: Arial; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-box { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 0 20px rgba(0,0,0,0.2); 
            width: 300px; 
        }
        .login-box h2 { color: #667eea; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #667eea; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🏥 Connexion Patient</h2>
        <?php if($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p style="text-align: center; margin-top: 20px; font-size: 12px;">
            Utilisateur: <strong>sarahj</strong><br>
            Mot de passe: <strong>password123</strong>
        </p>
    </div>
</body>
</html>