<?php
// Inclure la configuration de la base de données
include 'db_config.php';

// Récupérer les assistants depuis la base de données
try {
    // Connexion à la base de données
    $db = getPDOConnection();
    
    // Récupérer tous les assistants
    $stmt = $db->prepare("SELECT * FROM assistant");
    $stmt->execute();
    $assistants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List of Assistants</title>
  <link rel="stylesheet" href="ListofA.css">
</head>
<body>
  <header>
    <h1>List of Assistants</h1>
    <nav>
      <a href="Dash.html">Dashboard</a>
      <a href="AddA.php" class="add-btn">+ Add Assistant</a>
    </nav>
  </header>

  <main>
    <table id="assistantsTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Supervising Doctor</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Assistant rows will be added dynamically here -->
        <?php foreach ($assistants as $assistant): ?>
          <tr>
            <td><?= htmlspecialchars($assistant['assis_name'] . ' ' . $assistant['assis_lname']) ?></td>
            <td><?= htmlspecialchars($assistant['doctor_name']) ?></td>
            <td><?= htmlspecialchars($assistant['assis_phone']) ?></td>
            <td><?= htmlspecialchars($assistant['assis_email']) ?></td>
            <td>
              <span class="status <?= strtolower($assistant['status']) ?>"><?= htmlspecialchars($assistant['status']) ?></span>
            </td>
            <td class="actions">
              <button class="view" onclick="window.location.href='viewassistant.php?id=<?= $assistant['assis_id'] ?>'">View</button>
              <button class="edit" onclick="window.location.href='editassistant.php?id=<?= $assistant['assis_id'] ?>'">Edit</button>
              <button class="delete" onclick="window.location.href='deletassistant.php?id=<?= $assistant['assis_id'] ?>'">Delete</button>
              <button class="report" onclick="window.location.href='reportassistant.php?id=<?= $assistant['assis_id'] ?>'">Report</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>

  <script>
    // Ici, le code pour gérer l'interaction avec la page (si nécessaire)
  </script>
</body>
</html>

