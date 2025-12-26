<?php
// Inclure la configuration de la base de données
require_once 'db_config.php';
// Récupérer les docteurs depuis la base de données
try {
    // Connexion à la base de données
    $db = getPDOConnection();
    
    // Récupérer tous les docteurs
    $stmt = $db->prepare("SELECT * FROM doctor");
    $stmt->execute();
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Liste des Docteurs</title>
  <link rel="stylesheet" href="ListofD.css" />
</head>
<body>
  <header>
    <h1>Liste des Docteurs</h1>
    <nav>
      <a href="AddD.php" class="btn">Ajouter un docteur</a>
      <a href="Dash.html" class="btn">Dashboard</a>
    </nav>
  </header>

  <main>
    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Spécialité</th>
          <th>Téléphone</th>
          <th>Email</th>
          <th>Actions</th> 
        </tr>
      </thead>
      <tbody>
        <!-- Afficher les docteurs ici -->
        <?php foreach ($doctors as $doctor): ?>
          <tr>
            <td><?= htmlspecialchars($doctor['doc_name'] . ' ' . $doctor['doc_lname']) ?></td>
            <td><?= htmlspecialchars($doctor['doc_specialite']) ?></td>
            <td><?= htmlspecialchars($doctor['doc_telephone']) ?></td>
            <td><?= htmlspecialchars($doctor['doc_email']) ?></td>
            <td>
              <button onclick="window.location.href='viewdoctor.php?id=<?= $doctor['doc_id'] ?>'">Voir</button>
              <button onclick="window.location.href='editdoctor.php?id=<?= $doctor['doc_id'] ?>'">Modifier</button>
              <button onclick="deleteDoctor(<?= $doctor['doc_id'] ?>)">Supprimer</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>

  <script>
    function deleteDoctor(docId) {
      if (confirm("Voulez-vous vraiment supprimer ce docteur ?")) {
        // Envoyer une requête PHP pour supprimer le docteur
        fetch('DeletD.php?id=' + docId)
          .then(response => response.text())
          .then(data => {
            alert(data);
            window.location.reload(); // Recharger la page après suppression
          })
          .catch(error => alert('Erreur lors de la suppression : ' + error));
      }
    }
  </script>
</body>
</html>
