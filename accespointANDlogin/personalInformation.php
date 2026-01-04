<?php
session_start();

/* =========================
   HANDLE FORM SUBMIT
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['register']['first_name'] = $_POST['firstName'];
    $_SESSION['register']['last_name']  = $_POST['lastName'];
    $_SESSION['register']['birth_date'] = $_POST['birthDate'] ?? null;
    $_SESSION['register']['gender']     = $_POST['gender'] ?? null;

    header("Location: contactAndAddress.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal Information</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
</head>
<body class="personal-page">

  <div class="form-container">
    <h2>Personal Information</h2>

<form method="POST" onsubmit="return validatePersonalInfo();">


      <div class="photo-section">
        <label for="photo">PHOTO</label><br>
        <input type="file" id="photo" name="photo" accept="image/*">
      </div>

      <div class="form-group">
        <label>First Name</label>
        <input type="text" name="firstName" required>
      </div>

      <div class="form-group">
        <label>Last Name *</label>
        <input type="text" name="lastName" required>
      </div>

      <div class="form-group">
        <label>Date of Birth</label>
        <input type="date" name="birthDate">
      </div>

      <div class="form-group">
        <label>Gender</label>
        <select name="gender">
          <option value="">--Select--</option>
          <option>Male</option>
          <option>Female</option>
        </select>
      </div>

      <div class="form-group">
        <label>Blood Type</label>
        <select name="bloodType">
          <option value="">--Select--</option>
          <option>A+</option>
          <option>A-</option>
          <option>B+</option>
          <option>B-</option>
          <option>AB+</option>
          <option>AB-</option>
          <option>O+</option>
          <option>O-</option>
        </select>
      </div>

<button type="submit">Next Step</button>

    </form>
  </div>
</body>
</html>
