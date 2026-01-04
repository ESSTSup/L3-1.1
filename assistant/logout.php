<?php
session_start();

/* Destroy all session data */
session_unset();
session_destroy();

/* Redirect to login page */
header("Location: ../accespointANDlogin/account.html");
exit;


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="2;url=signin.php">
  <title>Déconnexion...</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>

</head>
<body>
  <h2>Vous avez été déconnecté.</h2>
  <p>Redirection vers la page de connexion...</p>
</body>
</html>

