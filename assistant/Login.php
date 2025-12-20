<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {

    $type = $_POST['type'];

    // Only allowed values
    if (!in_array($type, ['doctor', 'assistant', 'patient'])) {
        http_response_code(400);
        exit;
    }

    //  Store chosen access type
    $_SESSION['login_type']  = $type;
    $_SESSION['access_type'] = $type;

    echo "OK";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Choisir le type de compte</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background-color: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    h1 {
      color: #A6615A;
      margin-bottom: 10px;
    }

    p {
      color: #555;
      margin-bottom: 25px;
    }

    .options {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .btn {
      background-color: #A6615A;
      color: white;
      text-decoration: none;
      padding: 12px 18px;
      border-radius: 8px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #8d524d;
    }
  </style>
</head>

<body class="login-page">

  <div class="login-container">
    <h1>Connexion</h1>
    <p>Choisissez votre type de compte :</p>

    <div class="options">
      <button class="btn" onclick="goLogin('doctor')">Docteur</button>
      <button class="btn" onclick="goLogin('assistant')">Assistant</button>
      <button class="btn" onclick="goLogin('patient')">Patient</button>
    </div>
  </div>

</body>
</html>
