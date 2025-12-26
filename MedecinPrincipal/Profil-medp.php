<?php
// Inclure la configuration de la base de données
include('db_config.php');


$pdo = getPDOConnection();

// Vérifier si la méthode de la requête est POST pour insérer ou mettre à jour les données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password']; // Mot de passe de l'utilisateur
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // Hacher le mot de passe avant de l'insérer dans la base de données
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérifier si l'utilisateur existe déjà dans la base de données (par email ou autre critère)
    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Si l'utilisateur existe, mettre à jour son profil
        $stmt = $pdo->prepare("UPDATE doctors SET name = :name, password = :password, contact = :contact WHERE email = :email");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $hashedPassword); // Utiliser le mot de passe haché
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $message = "Profil mis à jour avec succès!";
    } else {
        // Si l'utilisateur n'existe pas, insérer de nouvelles données
        $stmt = $pdo->prepare("INSERT INTO doctors (name, password, contact, email) VALUES (:name, :password, :contact, :email)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $hashedPassword); // Utiliser le mot de passe haché
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $message = "Profil créé avec succès!";
    }
}

// Fonction pour obtenir la connexion PDO
function getPDOConnection() {
    try {
        // Connexion à la base de données avec vos informations
        $pdo = new PDO('mysql:host=localhost;dbname=medicalclinic', 'kenza', 'kenza05');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Médecin Principal</title>
    <link rel="stylesheet" href="Profil-medp.css">
</head>
<body>

<div class="profile-container">
    <h2>Profil Médecin Principal</h2>

    <!-- Affichage du message de confirmation -->
    <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>

    <!-- Formulaire de profil médecin -->
    <form id="profileForm" method="POST">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" value="<?= isset($password) ? htmlspecialchars($password) : '' ?>" required>

        <label for="contact">Contact :</label>
        <input type="text" id="contact" name="contact" value="<?= isset($contact) ? htmlspecialchars($contact) : '' ?>" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>

        <button type="submit" class="modify-btn">Suivant</button>
    </form>

    <a href="Dash.html" class="back-link">← Retour au Dashboard</a>
</div>

</body>
</html>
