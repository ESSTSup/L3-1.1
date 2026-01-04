<?php
session_start();

/* HANDLE SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['password'] !== $_POST['confirm']) {
        die("Passwords do not match");
    }

    $_SESSION['register']['password'] =
        password_hash($_POST['password'], PASSWORD_DEFAULT);

    header("Location: medicalinformation.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Security & Password</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
</head>
<body class="security-page">

  <div class="form-container">
    <h2>Security & Password</h2>

 <form method="POST" ">

  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    <ul class="password-rules">
      <li>At least 4 characters</li>
      <li>One uppercase letter</li>
      <li>One number</li>
    </ul>
  </div>

  <div class="form-group">
    <label for="confirm">Confirm Password</label>
    <input type="password" id="confirm" name="confirm" required>
  </div>

  <div class="form-buttons">
    <a href="contactAndAddress.php" class="btn previous">Previous</a>
    <button type="submit">Next Step</button>
  </div>

</form>

  </div>
</body>
</html>
