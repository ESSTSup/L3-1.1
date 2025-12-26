<?php
// Inclure la configuration de la base de données
require_once 'db_config.php';

// Variable pour stocker le message de succès ou d'erreur
$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient = $_POST['patient'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'];
    $certificate = $_POST['certificate'];
    $signature = $_POST['signature'];
    
    // Traiter les cases à cocher (exams)
    $exams = isset($_POST['exams']) ? implode(',', $_POST['exams']) : '';

    // Créer une connexion à la base de données
    try {
        $db = DBConfig::getPDOConnection();

        // Préparer la requête d'insertion dans la table consultation
        $stmt = $db->prepare("INSERT INTO consultation (patient, doctor, consultation_date, consultation_time, symptoms, notes, certificate, signature, exams) 
        VALUES (:patient, :doctor, :date, :time, :notes, :notes, :certificate, :signature, :exams)");

        // Lier les paramètres
        $stmt->bindParam(':patient', $patient);
        $stmt->bindParam(':doctor', $doctor);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':certificate', $certificate);
        $stmt->bindParam(':signature', $signature);
        $stmt->bindParam(':exams', $exams);

        // Exécuter la requête
        $stmt->execute();

        // Message de succès
        $message = "Consultation ajoutée avec succès!";
    } catch (Exception $e) {
        // Message d'erreur
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Consultation Management</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        margin: 0;
        padding: 30px;
      }

      h1 {
        text-align: center;
        color: #2b3a67;
        margin-bottom: 20px;
      }

      .container {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
      }

      form {
        display: flex;
        flex-direction: column;
        gap: 15px;
      }

      label {
        font-weight: bold;
        color: #333;
      }

      input,
      textarea,
      select {
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
        width: 100%;
      }

      textarea {
        resize: vertical;
        min-height: 80px;
      }

      .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
      }

      .checkbox-group label {
        font-weight: normal;
      }

      button {
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        background: #3e4148;
        color: white;
        cursor: pointer;
        font-size: 15px;
        transition: background 0.3s;
      }

      button:hover {
        background: #4056a1;
      }

      .message {
        margin-top: 20px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        color: green;
      }

      .error {
        color: red;
      }
    </style>
  </head>
  <body>
    <h1>Consultation Management</h1>

    <div class="container">
      <form id="consultationForm" action="Consultation Management.php" method="POST">
        <div>
          <label for="patient">Patient Name:</label>
          <input type="text" id="patient" name="patient" required />
        </div>

        <div>
          <label for="doctor">Doctor:</label>
          <input type="text" id="doctor" name="doctor" required />
        </div>

        <div>
          <label for="date">Date:</label>
          <input type="date" id="date" name="date" required />
        </div>

        <div>
          <label for="time">Time:</label>
          <input type="time" id="time" name="time" required />
        </div>

        <div>
          <label for="notes">Notes:</label>
          <textarea id="notes" name="notes" placeholder="Add doctor notes..." required></textarea>
        </div>

        <div>
          <label>Further Exams:</label>
          <div class="checkbox-group">
            <label><input type="checkbox" name="exams[]" value="Blood Test" /> Blood Test</label>
            <label><input type="checkbox" name="exams[]" value="Echo" /> Echo</label>
            <label><input type="checkbox" name="exams[]" value="X-Ray" /> X-Ray</label>
            <label><input type="checkbox" name="exams[]" value="Urine Test" /> Urine Test</label>
          </div>
        </div>

        <div>
          <label for="certificate">Medical Certificate:</label>
          <textarea id="certificate" name="certificate" placeholder="Write the medical certificate here..."></textarea>
        </div>

        <div>
          <label for="signature">Doctor’s Signature:</label>
          <input type="text" id="signature" name="signature" placeholder="_________________________" />
        </div>

        <button type="submit">Add Consultation</button>
      </form>

      <!-- Afficher le message de succès ou d'erreur -->
      <?php if ($message != ""): ?>
        <div class="message <?php echo strpos($message, 'Erreur') === false ? '' : 'error'; ?>">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>
    </div>
  </body>
</html>
