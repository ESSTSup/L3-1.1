<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['register']['allergies'] = $_POST['allergies'] ?? null;
    $_SESSION['register']['chronic']   = $_POST['chronicDiseases'] ?? null;
    $_SESSION['register']['emergency_name']  = $_POST['emergencyName'];
    $_SESSION['register']['emergency_phone'] = $_POST['emergencyPhone'];

    header("Location: register_patient.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medical Information</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
</head>
<body class="medical-page">

  <div class="form-container">
    <h2>Medical Information</h2>

   <form method="POST">

      <div class="form-group">
        <label>Allergies</label>
        <textarea id="allergies" name="allergies" rows="3" placeholder="List any allergies you have..."></textarea>
      </div>

      <div class="form-group">
        <label>Chronic Diseases</label>
        <textarea id="chronicDiseases" name="chronicDiseases" rows="3" placeholder="Ex: Diabetes, Hypertension..."></textarea>
      </div>

      <div class="form-group">
        <label>Emergency Contact Name</label>
        <input type="text" id="emergencyName" name="emergencyName" placeholder="Full Name" required>
      </div>

      <div class="form-group">
        <label>Emergency Contact Phone</label>
        <input type="tel" id="emergencyPhone" name="emergencyPhone" placeholder="+213 555 987 654" required>
      </div>

      <div class="form-buttons">
        <a href="securitypassword.php" class="btn previous">Previous</a>

        <!-- 🔥 Le bon bouton avec validation JS -->
      <button type="submit" class="next-step">
  Create & Book Appointment
</button>

      </div>
    </form>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Medical Appointment System — All rights reserved.</p>
  </footer>
</body>
</html>
