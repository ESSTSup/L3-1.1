<?php
// Inclure la configuration de la base de données
include('db_config.php');

// Vérifier si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées depuis le formulaire
    $patient = $_POST['patient'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $meds = $_POST['meds'];
    $notes = $_POST['notes'];
    $verified = isset($_POST['verified']) ? 1 : 0;

    // Connexion à la base de données
    $pdo = getPDOConnection();

    try {
        // Préparer la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO ordonnances (patient_name, doctor_name, ordonnance_date, meds, notes, verified) VALUES (:patient, :doctor, :date, :meds, :notes, :verified)");
        $stmt->bindParam(':patient', $patient);
        $stmt->bindParam(':doctor', $doctor);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':meds', $meds);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':verified', $verified, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();
        
        // Répondre avec succès
        echo json_encode(['success' => true, 'message' => 'Ordonnance enregistrée avec succès']);
    } catch (PDOException $e) {
        // Gérer l'erreur
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de l\'ordonnance: ' . $e->getMessage()]);
    }
    exit;
}

// Fonction pour obtenir la connexion PDO
function getPDOConnection() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion d’Ordonnances</title>
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
      }

      .container {
        max-width: 950px;
        margin: 20px auto;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
      }

      .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
      }

      .top-bar input {
        padding: 10px;
        width: 60%;
        border: 1px solid #ccc;
        border-radius: 6px;
      }

      .top-bar button {
        background: #212328;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
      }

      .top-bar button:hover {
        background: #34363f;
      }

      form {
        display: none;
        flex-direction: column;
        gap: 10px;
        background: #f9fafb;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
      }

      label {
        font-weight: bold;
        color: #333;
      }

      input,
      textarea {
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
        font-size: 14px;
      }

      textarea {
        resize: vertical;
        min-height: 80px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
      }

      th,
      td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
        vertical-align: top;
      }

      th {
        background: #6c6d70;
        color: white;
      }

      .actions button {
        margin-right: 5px;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
        color: white;
      }

      .edit {
        background: #f4b400;
      }
      .delete {
        background: #d9534f;
      }
      .verify {
        background: #28a745;
      }
    </style>
  </head>
  <body>
    <h1>Gestion des Ordonnances</h1>

    <div class="container">
      <div class="top-bar">
        <input
          type="text"
          id="search"
          placeholder="🔍 Rechercher patient / médicament..."
        />
        <button id="newBtn">Nouvelle ordonnance</button>
      </div>

      <form id="ordonnanceForm">
        <label>Nom du patient</label>
        <input type="text" id="patient" required />

        <label>Médecin</label>
        <input type="text" id="doctor" required />

        <label>Date</label>
        <input type="date" id="date" required />

        <label>Médicaments (un par ligne)</label>
        <textarea
          id="meds"
          placeholder="Ex: Paracetamol 500mg - 1 comprimé chaque 8h"
        ></textarea>

        <label>Notes / Instructions</label>
        <textarea
          id="notes"
          placeholder="Ajouter des instructions..."
        ></textarea>

        <label><input type="checkbox" id="verified" /> Vérifié</label>

        <div>
          <button
            type="submit"
            style="
              background: #60626a;
              color: white;
              border: none;
              padding: 10px 15px;
              border-radius: 6px;
            "
          >
            Enregistrer
          </button>
          <button
            type="button"
            id="cancelBtn"
            style="
              background: #444241;
              color: white;
              border: none;
              padding: 10px 15px;
              border-radius: 6px;
            "
          >
            Annuler
          </button>
        </div>
      </form>

      <table>
        <thead>
          <tr>
            <th>Patient</th>
            <th>Médecin</th>
            <th>Date</th>
            <th>Médicaments</th>
            <th>Notes</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="ordonnanceTable"></tbody>
      </table>
    </div>

    <script>
      const form = document.getElementById("ordonnanceForm");
      const newBtn = document.getElementById("newBtn");
      const cancelBtn = document.getElementById("cancelBtn");
      const tableBody = document.getElementById("ordonnanceTable");
      const search = document.getElementById("search");
      let editIndex = null;

      function loadData() {
        fetch('manage_ordonnance.php', {
          method: 'GET',
        })
          .then(response => response.json())
          .then(data => {
            const filter = search.value.toLowerCase();
            tableBody.innerHTML = "";

            data
              .filter(
                (o) =>
                  o.patient.toLowerCase().includes(filter) ||
                  o.meds.toLowerCase().includes(filter)
              )
              .forEach((o, index) => {
                const row = `
                <tr>
                  <td>${o.patient}</td>
                  <td>${o.doctor}</td>
                  <td>${o.date}</td>
                  <td>${o.meds.replace(/\n/g, "<br>")}</td>
                  <td>${o.notes}</td>
                  <td>${o.verified ? "✅ Vérifiée" : "❌ Non vérifiée"}</td>
                  <td class="actions">
                    <button class="edit" onclick="editOrdonnance(${index})">Modifier</button>
                    <button class="delete" onclick="deleteOrdonnance(${index})">Supprimer</button>
                    <button class="verify" onclick="verifyOrdonnance(${index})">Vérifier</button>
                  </td>
                </tr>
              `;
                tableBody.innerHTML += row;
              });
          })
          .catch(error => {
            console.error("Error fetching ordonnances:", error);
          });
      }

      function saveData(data) {
        fetch('manage_ordonnance.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `patient=${encodeURIComponent(data.patient)}&doctor=${encodeURIComponent(data.doctor)}&date=${encodeURIComponent(data.date)}&meds=${encodeURIComponent(data.meds)}&notes=${encodeURIComponent(data.notes)}&verified=${data.verified}`
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              loadData(); // Actualiser la liste des ordonnances
              form.reset();
              form.style.display = "none";
            } else {
              alert("Erreur lors de l'enregistrement de l'ordonnance.");
            }
          })
          .catch(error => {
            console.error("Error saving ordonnance:", error);
            alert("Erreur lors de l'enregistrement.");
          });
      }

      form.addEventListener("submit", (e) => {
        e.preventDefault();

        const patient = document.getElementById("patient").value;
        const doctor = document.getElementById("doctor").value;
        const date = document.getElementById("date").value;
        const meds = document.getElementById("meds").value;
        const notes = document.getElementById("notes").value;
        const verified = document.getElementById("verified").checked;

        const data = { patient, doctor, date, meds, notes, verified };

        if (editIndex !== null) {
          data.id = editIndex;
        }

        saveData(data);
      });

      function editOrdonnance(index) {
        // Cette fonction va remplir le formulaire avec les données de l'ordonnance à modifier
        fetch('manage_ordonnance.php', {
          method: 'GET',
        })
          .then(response => response.json())
          .then(data => {
            const o = data[index];
            document.getElementById("patient").value = o.patient;
            document.getElementById("doctor").value = o.doctor;
            document.getElementById("date").value = o.date;
            document.getElementById("meds").value = o.meds;
            document.getElementById("notes").value = o.notes;
            document.getElementById("verified").checked = o.verified;
            editIndex = index;
            form.style.display = "flex";
          })
          .catch(error => {
            console.error("Error fetching ordonnance:", error);
          });
      }

      function deleteOrdonnance(index) {
        if (confirm("Supprimer cette ordonnance ?")) {
          fetch('manage_ordonnance.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `index=${index}`,
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                loadData(); // Recharger la liste
              } else {
                alert("Erreur lors de la suppression de l'ordonnance.");
              }
            })
            .catch(error => {
              console.error("Error deleting ordonnance:", error);
              alert("Erreur lors de la suppression.");
            });
        }
      }

      function verifyOrdonnance(index) {
        fetch('manage_ordonnance.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `index=${index}&verified=true`,
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              loadData();
            } else {
              alert("Erreur lors de la vérification de l'ordonnance.");
            }
          })
          .catch(error => {
            console.error("Error verifying ordonnance:", error);
            alert("Erreur lors de la vérification.");
          });
      }

      newBtn.addEventListener("click", () => {
        form.style.display = "flex";
        form.reset();
        editIndex = null;
      });

      cancelBtn.addEventListener("click", () => {
        form.style.display = "none";
        form.reset();
      });

      search.addEventListener("input", loadData);

      loadData();
    </script>
  </body>
</html>
