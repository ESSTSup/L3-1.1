<?php
session_start();
require_once "../database/db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        die("Invalid request");
    }

    $db = DatabaseConfig::getPDOConnection();

    $stmt = $db->prepare(
        "SELECT clinic_id, clinic_password 
         FROM clinics 
         WHERE clinic_email = ? AND archived = 0"
    );
    $stmt->execute([$email]);
    $clinic = $stmt->fetch();

    if (!$clinic) {
        die("Clinic not found");
    }

    if (!password_verify($password, $clinic['clinic_password'])) {
        die("Wrong password");
    }

    //  STORE CLINIC CONTEXT
    $_SESSION['clinic_id'] = $clinic['clinic_id'];
    $_SESSION['login_type'] = 'staff';

    header("Location: profil.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Clinique</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">
  <div class="login-container">
    <h1>Connexion Clinique</h1>

    <form method="POST">
      <input type="email" name="email" placeholder="Email Clinique" required>   
      <input type="password" name="password" placeholder="Mot de passe Clinique" required>
      <button type="submit">Entrer</button>
    </form>
  </div>
</body>
</html>
