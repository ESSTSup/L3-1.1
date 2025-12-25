<?php
// Inclure le fichier de configuration de la base de données
require_once 'db_config.php';

// Traitement du formulaire d'ajout de docteur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $speciality = $_POST['speciality'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Sécuriser le mot de passe

    // Validation des champs
    if (empty($name) || empty($speciality) || empty($phone) || empty($email) || empty($password)) {
        echo "Veuillez remplir tous les champs!";
        exit;
    }

    // Connexion à la base de données
    try {
        $pdo = DBConfig::getPDOConnection();  // Connexion PDO

        // Préparer la requête pour insérer les données dans la table 'doctor'
        $sql = "INSERT INTO doctor (doc_name, doc_specialite, doc_telephone, doc_email, doc_password) 
                VALUES (:name, :speciality, :phone, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':speciality' => $speciality,
            ':phone' => $phone,
            ':email' => $email,
            ':password' => $password
        ]);

        // Rediriger après l'ajout
        echo "Docteur ajouté avec succès!";
        header('Location: ListofD.html');  // Redirection vers la liste des docteurs
        exit;
        
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout du docteur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un Docteur</title>
  <link rel="stylesheet" href="AddD.css" />
</head>
<body>
  <div class="card">
    <h2>Ajouter un Docteur</h2>

    <form id="doctorForm" method="POST" action="">
      <label for="name">Nom :</label>
      <input type="text" id="name" name="name" required />

      <label for="speciality">Spécialité :</label>
      <input type="text" id="speciality" name="speciality" required />

      <label for="phone">Téléphone :</label>
      <input type="text" id="phone" name="phone" required />

      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required />

      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required />

      <button type="submit" class="btn">Ajouter</button>
    </form>
  </div>

  <script>
    document.getElementById("doctorForm").addEventListener("submit", function (e) {
      e.preventDefault();

      // Récupérer les données du formulaire (bien que maintenant elles sont envoyées directement à PHP)
      const doctorData = {
        name: document.getElementById("name").value,
        speciality: document.getElementById("speciality").value,
        phone: document.getElementById("phone").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
      };

      // Le formulaire va maintenant soumettre les données via POST au fichier PHP
    });

    function validateForm() {
      const name = document.getElementById("name").value.trim();
      const speciality = document.getElementById("speciality").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim(); 

      if (!name || !speciality || !phone || !email || !password) {
        alert("Veuillez remplir tous les champs !");
        return false;
      }

      return true;
    }

    const inputs = document.querySelectorAll("#doctorForm input");

    inputs.forEach((input) => {
      input.addEventListener("focus", () => {
        input.style.border = "2px solid #3498db";
      });

      input.addEventListener("blur", () => {
        input.style.border = "1px solid #ccc";
      });
    });
  </script>

</body>
</html>
