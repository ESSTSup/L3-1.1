<?php
session_start();
require_once "../database/db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $clinic_id = trim($_POST['clinic_id'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    if ($clinic_id === '' || $password === '') {
        die("Requête invalide");
    }

    $db = Database::getInstance()->getConnection();

    $stmt = $db->prepare("SELECT * FROM clinics WHERE clinic_id = ?");
    $stmt->bind_param("i", $clinic_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows !== 1) {
        die("Clinique introuvable");
    }

    $clinic = $res->fetch_assoc();

    // plain text password (test mode)
    if ($password !== $clinic['clinic_password']) {
        die("Mot de passe incorrect");
    }

    //  PRESERVE EXISTING FLOW
    if (!isset($_SESSION['login_type'])) {
        header("Location: Login.php");
        exit;
    }

    //  SESSION OK
    $_SESSION['clinic_id'] = $clinic['clinic_id'];

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
      <input type="text" name="clinic_id" placeholder="ID Clinique" required>
      <input type="password" name="password" placeholder="Mot de passe Clinique" required>
      <button type="submit">Entrer</button>
    </form>
  </div>
</body>
</html>
