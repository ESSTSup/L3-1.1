<?php
session_start();

/* HANDLE SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['register']['email']   = $_POST['email'];
    $_SESSION['register']['phone']   = $_POST['phone'];
    $_SESSION['register']['address'] = $_POST['address'] ?? null;
    $_SESSION['register']['city']    = $_POST['city'] ?? null;
    $_SESSION['register']['postal']  = $_POST['postalCode'] ?? null;

    header("Location: securitypassword.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact and Address</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
</head>
<body class="contact-page">


  <!-- Header -->
  <header>
    <h1>Medical Appointment System</h1>
    <nav>
      <a href="index (1).html">Home</a> |
      <a href="account.html">Account</a> |
      <a href="contactAndAddress.php">Contact</a>
    </nav>
  </header>

  <!-- Main Content -->
  <div class="form-container">
    <h2>Contact and Address</h2>

   <form method="POST" >

      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="example@email.com" required>
      </div>

      <div class="form-group">
        <label>Phone Number</label>
        <input type="tel" name="phone" placeholder="+213 555 123 456" required>
      </div>

      <div class="form-group">
        <label>Full Address</label>
        <input type="text" name="address" placeholder="123 Rue de la Santé, Alger">
      </div>

      <div class="form-group">
        <label>City</label>
        <input type="text" name="city" placeholder="Alger">
      </div>

      <div class="form-group">
        <label>Postal Code</label>
        <input type="text" name="postalCode" placeholder="16000">
      </div>

      <div class="form-buttons">
        <a href="personalInformation.php" class="btn previous">Previous</a>
 <button type="submit">Next Step</button>

      </div> 
    </form>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Medical Appointment System — All rights reserved.</p>
  </footer>

</body>
</html>
