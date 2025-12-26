<?php
// Inclure la configuration de la base de données
include('db_config.php');

// Vérifier si l'ID du docteur est passé dans l'URL
if (!isset($_GET['id'])) {
    die('ID du docteur non fourni.');
}

// Récupérer l'ID du docteur depuis l'URL
$doctorId = $_GET['id'];

// Connexion à la base de données
$pdo = getPDOConnection();

$doctorData = null;

// Récupérer les informations du docteur depuis la base de données
try {
    $stmt = $pdo->prepare("SELECT * FROM doctor WHERE doc_id = :doctor_id");
    $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_INT);
    $stmt->execute();
    $doctorData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données du docteur : " . $e->getMessage();
    exit;
}

// Si le formulaire a été soumis, mettre à jour les informations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $speciality = $_POST['speciality'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    // Validation des données
    if (empty($name) || empty($speciality) || empty($phone) || empty($email) || empty($status)) {
        echo "Tous les champs sont obligatoires!";
        exit;
    }

    // Mettre à jour les informations du docteur dans la base de données
    try {
        $updateStmt = $pdo->prepare("UPDATE doctor SET doc_name = :name, doc_specialite = :speciality, doc_telephone = :phone, doc_email = :email, doc_status = :status WHERE doc_id = :doctor_id");
        $updateStmt->bindParam(':name', $name);
        $updateStmt->bindParam(':speciality', $speciality);
        $updateStmt->bindParam(':phone', $phone);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->bindParam(':status', $status);
        $updateStmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_INT);
        $updateStmt->execute();

        echo "Les informations du docteur ont été mises à jour avec succès!";
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour des informations du docteur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Docteur</title>
  <link rel="stylesheet" href="ModifyD.css">
</head>
<body>

  <div class="card">
    <h2>Modifier un Docteur</h2>

    <form method="POST">
      <label>Nom :</label>
      <input type="text" name="name" value="<?= htmlspecialchars($doctorData['doc_name'] ?? '') ?>" required>

      <label>Spécialité :</label>
      <input type="text" name="speciality" value="<?= htmlspecialchars($doctorData['doc_specialite'] ?? '') ?>" required>

      <label>Téléphone :</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($doctorData['doc_telephone'] ?? '') ?>" required>

      <label>Email :</label>
      <input type="email" name="email" value="<?= htmlspecialchars($doctorData['doc_email'] ?? '') ?>" required>

      <label>Statut :</label>
      <select name="status" required>
        <option value="Normal" <?= $doctorData['doc_status'] === 'Normal' ? 'selected' : '' ?>>Normal</option>
        <option value="Admin" <?= $doctorData['doc_status'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
      </select>

      <button type="submit">OK</button>
    </form>
  </div>

</body>
</html>
