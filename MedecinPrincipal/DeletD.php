<?php
// Inclure la configuration de la base de données
require_once 'db_config.php';

if (isset($_GET['id'])) {
    $doctorId = $_GET['id'];

    try {
        // Connexion à la base de données
        $db = getPDOConnection();

        // Requête pour récupérer le docteur avec l'ID spécifié
        $stmt = $db->prepare("SELECT * FROM doctor WHERE doc_id = :doc_id");
        $stmt->bindParam(':doc_id', $doctorId, PDO::PARAM_INT);
        $stmt->execute();
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($doctor) {
            $doctorName = $doctor['doc_name'] . ' ' . $doctor['doc_lname'];
        } else {
            echo "Docteur non trouvé.";
            exit;
        }

        // Si la confirmation est envoyée, supprimer le docteur de la base de données
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $db->prepare("DELETE FROM doctor WHERE doc_id = :doc_id");
            $stmt->bindParam(':doc_id', $doctorId, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: ListofD.php"); // Rediriger vers la liste des docteurs après suppression
            exit;
        }
    } catch (Exception $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
} else {
    echo "Aucun ID de docteur spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Supprimer un docteur</title>
  <link rel="stylesheet" href="DeletD.css" />
</head>
<body>
  <div class="card">
    <h2>Supprimer un docteur</h2>
    <p id="doctorName"><?= htmlspecialchars($doctorName) ?></p>
    <p>Êtes-vous sûr de vouloir supprimer ce docteur ?</p>

    <div class="buttons">
      <button id="cancelBtn" onclick="window.location.href='ListofD.php'">Non</button>
      <form method="POST" style="display: inline;">
        <button type="submit" id="confirmBtn">Oui</button>
      </form>
    </div>
  </div>
</body>
</html>
