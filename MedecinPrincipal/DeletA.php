<?php
// Inclure la configuration de la base de données
require_once 'db_config.php';

// Vérifier si un ID est passé en GET
$assistantId = isset($_GET['id']) ? $_GET['id'] : null;

$message = '';
if ($assistantId) {
    try {
        // Connexion à la base de données
        $db = getPDOConnection();

        // Suppression de l'assistant dans la base de données
        $stmt = $db->prepare("DELETE FROM assistant WHERE assis_id = :id");
        $stmt->bindParam(':id', $assistantId);
        $stmt->execute();

        $message = "Assistant deleted successfully!";
    } catch (Exception $e) {
        $message = "Error deleting assistant: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Assistant</title>
  <style>
    /* Style de base pour la page */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    /* Container de confirmation */
    #confirmation {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
    }

    /* Style du texte de confirmation */
    #assistantInfo {
      font-size: 18px;
      margin-bottom: 20px;
      color: #333;
    }

    /* Boutons */
    button {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      width: 48%;
      margin-top: 10px;
    }

    button:hover {
      background-color: #45a049;
    }

    /* Bouton de cancel (annuler) */
    button[type="button"] {
      background-color: #f44336;
    }

    button[type="button"]:hover {
      background-color: #e53935;
    }

    /* Container des boutons */
    .button-container {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h1>Delete Assistant</h1>

  <div id="confirmation">
    <p id="assistantInfo"><?php echo $message ? $message : "Are you sure you want to delete this assistant?"; ?></p>
    <div class="button-container">
      <button onclick="deleteAssistant()">Confirm Deletion</button>
      <button type="button" onclick="window.location.href='ListofA.html'">Cancel</button>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const id = parseInt(urlParams.get('id')); // Get the ID from the URL
      let assistants = JSON.parse(localStorage.getItem("assistants")) || [];
      const assistant = assistants.find(a => a.id === id);

      if (assistant) {
        document.getElementById("assistantInfo").innerHTML = `Are you sure you want to delete ${assistant.name}?`;
      } else {
        document.getElementById("assistantInfo").innerHTML = "Assistant not found.";
      }

      window.deleteAssistant = function() {
        if (assistant) {
          // Delete the assistant from localStorage
          assistants = assistants.filter(a => a.id !== id);
          localStorage.setItem("assistants", JSON.stringify(assistants));
          alert(`${assistant.name} has been deleted.`);
          window.location.href = 'ListofA.php'; // Redirect to the list after deletion
        }
      };
    });
  </script>

</body>
</html>


