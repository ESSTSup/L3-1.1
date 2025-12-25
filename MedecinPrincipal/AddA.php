<?php
require_once 'db_config.php'; // Inclure la configuration de la base de données

// Traitement du formulaire d'ajout d'assistant
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $doctor = $_POST['doctor'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    // Validation des champs
    if (empty($name) || empty($doctor) || empty($phone) || empty($email)) {
        echo "Tous les champs sont obligatoires!";
        exit;
    }

    // Connexion à la base de données
    try {
        $pdo = DBConfig::getPDOConnection();  // Connexion à la base de données

        // Préparer la requête pour insérer les données dans la table 'assistant'
        $sql = "INSERT INTO assistant (assis_name, assis_lname, assis_email) VALUES (:name, :doctor, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':doctor' => $doctor,
            ':email' => $email
        ]);

        // Redirection après insertion réussie
        echo "Assistant ajouté avec succès!";
        header('Location: ListofA.html');
        exit;

    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de l'assistant : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Assistant</title>
  <link rel="stylesheet" href="AddA.css">
</head>
<body>
  <header>
    <h1>Add New Assistant</h1>
  </header>

  <main>
    <form id="addAssistantForm" class="form-box" method="POST" action="">
      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" placeholder="Enter assistant's name" required>

      <label for="doctor">Supervising Doctor:</label>
      <input type="text" id="doctor" name="doctor" placeholder="Enter supervising doctor's name" required>

      <label for="phone">Phone Number:</label>
      <input type="text" id="phone" name="phone" placeholder="+213 ..." required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="example@clinic.com" required>

      <label for="status">Status:</label>
      <select id="status" name="status">
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>

      <div class="buttons">
        <button type="submit" class="save">Save</button>
        <a href="ListofA.html" class="cancel">Cancel</a>
      </div>
    </form>
  </main>

  <script>
    document.getElementById('addAssistantForm').addEventListener('submit', function(event) {
      event.preventDefault(); 
      
      const name = document.getElementById('name').value;
      const doctor = document.getElementById('doctor').value;
      const phone = document.getElementById('phone').value;
      const email = document.getElementById('email').value;
      const status = document.getElementById('status').value;
      
      if (!name || !doctor || !phone || !email) {
        alert('Please fill in all required fields.');
        return;
      }
      
      const phoneRegex = /^\+213[5-7][0-9]{8}$/;
      if (!phoneRegex.test(phone)) {
        alert('Please enter a valid Algerian phone number (format: +213XXXXXXXXX)');
        return;
      }
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
      }
      

    });
  </script>
</body>
</html>

