<?php
// PatientManagement.php - Adapté pour votre base de données
session_start();

// Vérifier que l'utilisateur est connecté en tant que docteur
if (!isset($_SESSION['doc_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'PatientManager.php';
require_once 'SubscriptionManager.php';

$patientManager = new PatientManager();
$subscriptionManager = new SubscriptionManager();

$docId = $_SESSION['doc_id'];

// Récupérer les infos d'abonnement
$subscription = $subscriptionManager->getCurrentSubscription($docId);
$patientCount = $patientManager->countPatients($docId);
$canAddResult = $subscriptionManager->canAddPatient($docId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion des Patients</title>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 30px;
    }

    h1 {
      text-align: center;
      color: #2b3a67;
      margin-bottom: 10px;
    }

    p.subtitle {
      text-align: center;
      color: #555;
      margin-bottom: 30px;
    }

    .subscription-info {
      text-align: center;
      margin-bottom: 20px;
      padding: 10px;
      background: #e3f2fd;
      border-radius: 8px;
      color: #1565c0;
      max-width: 1000px;
      margin: 0 auto 20px;
    }

    .subscription-info.warning {
      background: #fff3cd;
      color: #856404;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      gap: 10px;
    }

    .top-bar input {
      flex: 1;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .top-bar button {
      background: #4e515a;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
    }

    .top-bar button:hover {
      background: #1a1a1d;
    }

    .top-bar button:disabled {
      background: #ccc;
      cursor: not-allowed;
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

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    label {
      font-weight: bold;
      color: #333;
      margin-top: 5px;
    }

    input, select, textarea {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      width: 100%;
    }

    .patient-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 15px;
    }

    .patient-card {
      background: #fafafa;
      border-radius: 10px;
      border: 1px solid #ddd;
      padding: 15px;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .patient-header {
      font-size: 18px;
      font-weight: bold;
      color: #6e7485;
    }

    .patient-info {
      margin: 10px 0;
      color: #333;
      line-height: 1.6;
    }

    .patient-dates {
      font-size: 13px;
      color: #666;
    }

    .actions {
      margin-top: 10px;
      display: flex;
      justify-content: flex-end;
      gap: 8px;
    }

    .actions button {
      border: none;
      padding: 6px 10px;
      border-radius: 5px;
      cursor: pointer;
      color: white;
    }

    .edit { background: #f4b400; }
    .delete { background: #d9534f; }
    .view { background: #5cb85c; }

    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 500px;
      width: 90%;
      max-height: 80vh;
      overflow-y: auto;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h2 {
      color: #2b3a67;
    }

    .close {
      cursor: pointer;
      font-size: 20px;
      font-weight: bold;
    }

    .consultation {
      background: #f7f7f7;
      padding: 10px;
      margin-top: 10px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    .consultation-date {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
    }

    .message {
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      display: none;
    }

    .message.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .back-btn {
      display: inline-block;
      padding: 10px 20px;
      background: #6c757d;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .back-btn:hover {
      background: #5a6268;
    }

    .loading {
      text-align: center;
      padding: 20px;
      color: #666;
    }
  </style>
</head>
<body>
  <a href="dash.html" class="back-btn">← Retour au tableau de bord</a>

  <h1>Gestion des Patients</h1>
  <p class="subtitle">Gérez et suivez vos patients efficacement</p>

  <?php if ($subscription): ?>
  <div class="subscription-info <?= $canAddResult['can_add'] ? '' : 'warning' ?>">
    📊 Plan actuel: <strong><?= htmlspecialchars($subscription['plan_name']) ?></strong> 
    | Patients: <strong><?= $patientCount ?></strong>
    <?php if ($subscription['max_patients']): ?>
      / <?= $subscription['max_patients'] ?>
    <?php else: ?>
      (Illimité)
    <?php endif; ?>
    <?php if (!$canAddResult['can_add']): ?>
      | ⚠️ <?= htmlspecialchars($canAddResult['message']) ?>
      <a href="ManageSubscription.php" style="color: #0056b3; text-decoration: underline;">Améliorer</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <div class="container">
    <div id="messageBox" class="message"></div>

    <div class="top-bar">
      <input
        type="text"
        id="search"
        placeholder="🔍 Rechercher par nom, téléphone ou email..."
      />
      <button id="addPatientBtn" <?= $canAddResult['can_add'] ? '' : 'disabled' ?>>
        Ajouter un Patient
      </button>
    </div>

    <form id="patientForm">
      <input type="hidden" id="patientId" value="" />
      
      <div class="form-row">
        <div>
          <label>Nom *</label>
          <input type="text" id="name" required />
        </div>
        <div>
          <label>Prénom *</label>
          <input type="text" id="lname" required />
        </div>
      </div>

      <label>Date de naissance *</label>
      <input type="date" id="birthday" required />

      <label>Genre *</label>
      <select id="gender" required>
        <option value="">Sélectionner le genre</option>
        <option value="Male">Homme</option>
        <option value="Female">Femme</option>
      </select>

      <label>Téléphone *</label>
      <input type="text" id="phone" required />

      <label>Email *</label>
      <input type="email" id="email" required />

      <div style="display: flex; gap: 10px; justify-content: flex-end">
        <button type="submit" style="background: #29292b; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
          Enregistrer
        </button>
        <button
          type="button"
          id="cancelBtn"
          style="background: #5f5b5b; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;"
        >
          Annuler
        </button>
      </div>
    </form>

    <div id="patientList" class="patient-list">
      <div class="loading">Chargement des patients...</div>
    </div>
  </div>

  <!-- Modal Consultations -->
  <div id="consultationModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Consultations</h2>
        <span class="close" onclick="closeModal()">&times;</span>
      </div>
      <div id="consultationList"></div>
      <hr />
      <h3>Ajouter une Consultation</h3>
      <textarea
        id="consultationNotes"
        placeholder="Écrire les notes du docteur ici..."
        rows="4"
        style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;"
      ></textarea>
      <button
        id="saveConsultation"
        style="
          background: #494d59;
          color: white;
          margin-top: 10px;
          padding: 8px 16px;
          border: none;
          border-radius: 6px;
          cursor: pointer;
        "
      >
        Enregistrer la Consultation
      </button>
    </div>
  </div>

  <script>
    const patientForm = document.getElementById("patientForm");
    const addPatientBtn = document.getElementById("addPatientBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    const patientList = document.getElementById("patientList");
    const search = document.getElementById("search");
    const modal = document.getElementById("consultationModal");
    const consultationList = document.getElementById("consultationList");
    const consultationNotes = document.getElementById("consultationNotes");
    const saveConsultation = document.getElementById("saveConsultation");
    const messageBox = document.getElementById("messageBox");
    const patientId = document.getElementById("patientId");

    let currentPatientId = null;

    // Fonction pour afficher les messages
    function showMessage(message, type) {
      messageBox.textContent = message;
      messageBox.className = 'message ' + type;
      messageBox.style.display = 'block';
      
      setTimeout(() => {
        messageBox.style.display = 'none';
      }, 5000);
    }

    // Calculer l'âge à partir de la date de naissance
    function calculateAge(birthday) {
      const today = new Date();
      const birthDate = new Date(birthday);
      let age = today.getFullYear() - birthDate.getFullYear();
      const m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      return age;
    }

    // Charger les patients
    async function loadPatients() {
      try {
        const searchQuery = search.value;
        const response = await fetch(`handle_patient.php?action=get_all&search=${encodeURIComponent(searchQuery)}`);
        const result = await response.json();

        if (result.success) {
          patientList.innerHTML = "";
          
          if (result.patients.length === 0) {
            patientList.innerHTML = '<div class="loading">Aucun patient trouvé</div>';
            return;
          }

          result.patients.forEach(p => {
            const fullName = `${p.pat_name} ${p.pat_lname}`;
            const genderText = p.pat_gender === 'Male' ? 'Homme' : 'Femme';
            
            const card = document.createElement("div");
            card.className = "patient-card";
            card.innerHTML = `
              <div>
                <div class="patient-header">${fullName}</div>
                <div class="patient-info">
                  ${p.age} ans • ${genderText}<br>
                  📞 ${p.telephone}<br>
                  ✉️ ${p.pat_email}
                </div>
                <div class="patient-dates">
                  <strong>Né(e) le:</strong> ${formatDate(p.pat_birthday)}
                </div>
              </div>
              <div class="actions">
                <button class="view" onclick="viewConsultations(${p.pat_id})">Consultations</button>
                <button class="edit" onclick="editPatient(${p.pat_id})">Modifier</button>
                <button class="delete" onclick="deletePatient(${p.pat_id})">Supprimer</button>
              </div>
            `;
            patientList.appendChild(card);
          });
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur de chargement des patients', 'error');
        console.error(error);
      }
    }

    // Formater les dates
    function formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR');
    }

    // Soumettre le formulaire
    patientForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData();
      const currentPatientId = patientId.value;
      
      formData.append('action', currentPatientId ? 'update' : 'add');
      if (currentPatientId) {
        formData.append('patient_id', currentPatientId);
      }
      formData.append('name', document.getElementById('name').value);
      formData.append('lname', document.getElementById('lname').value);
      formData.append('birthday', document.getElementById('birthday').value);
      formData.append('gender', document.getElementById('gender').value);
      formData.append('phone', document.getElementById('phone').value);
      formData.append('email', document.getElementById('email').value);

      try {
        const response = await fetch('handle_patient.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();

        if (result.success) {
          showMessage(result.message, 'success');
          patientForm.reset();
          patientForm.style.display = "none";
          patientId.value = '';
          loadPatients();
          
          // Recharger si on a atteint la limite
          if (result.message.includes('limite')) {
            setTimeout(() => location.reload(), 2000);
          }
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur lors de l\'enregistrement', 'error');
        console.error(error);
      }
    });

    // Modifier un patient
    async function editPatient(id) {
      try {
        const response = await fetch(`handle_patient.php?action=get_one&patient_id=${id}`);
        const result = await response.json();

        if (result.success) {
          const p = result.patient;
          patientId.value = p.pat_id;
          document.getElementById('name').value = p.pat_name;
          document.getElementById('lname').value = p.pat_lname;
          document.getElementById('birthday').value = p.pat_birthday;
          document.getElementById('gender').value = p.pat_gender;
          document.getElementById('phone').value = p.telephone;
          document.getElementById('email').value = p.pat_email;
          patientForm.style.display = "flex";
          window.scrollTo(0, 0);
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur de chargement du patient', 'error');
      }
    }

    // Supprimer un patient
    async function deletePatient(id) {
      if (!confirm("Êtes-vous sûr de vouloir supprimer ce patient ?")) return;

      const formData = new FormData();
      formData.append('action', 'delete');
      formData.append('patient_id', id);

      try {
        const response = await fetch('handle_patient.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();

        if (result.success) {
          showMessage(result.message, 'success');
          loadPatients();
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur lors de la suppression', 'error');
      }
    }

    // Voir les consultations
    async function viewConsultations(id) {
      currentPatientId = id;
      
      try {
        const response = await fetch(`handle_patient.php?action=get_consultations&patient_id=${id}`);
        const result = await response.json();

        if (result.success) {
          consultationList.innerHTML = "";
          
          if (result.consultations.length === 0) {
            consultationList.innerHTML = '<p>Aucune consultation enregistrée</p>';
          } else {
            result.consultations.forEach((c, i) => {
              const div = document.createElement("div");
              div.className = "consultation";
              div.innerHTML = `
                <strong>Consultation ${i + 1}</strong>
                ${c.doctor_name ? ` par Dr. ${c.doctor_name}` : ''}<br>
                ${c.notes || 'Aucune note'}
                <div class="consultation-date">
                  ${new Date(c.consultation_date + ' ' + c.consultation_time).toLocaleString('fr-FR')}
                </div>
              `;
              consultationList.appendChild(div);
            });
          }
          
          modal.style.display = "flex";
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur de chargement des consultations', 'error');
      }
    }

    // Enregistrer une consultation
    saveConsultation.addEventListener("click", async () => {
      const notes = consultationNotes.value.trim();
      if (!notes) {
        alert("Veuillez écrire des notes!");
        return;
      }

      const formData = new FormData();
      formData.append('action', 'add_consultation');
      formData.append('patient_id', currentPatientId);
      formData.append('notes', notes);

      try {
        const response = await fetch('handle_patient.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();

        if (result.success) {
          consultationNotes.value = "";
          viewConsultations(currentPatientId);
          showMessage('Consultation ajoutée avec succès', 'success');
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur lors de l\'enregistrement', 'error');
      }
    });

    // Fermer le modal
    function closeModal() {
      modal.style.display = "none";
      consultationNotes.value = "";
    }

    window.onclick = (e) => {
      if (e.target == modal) closeModal();
    };

    // Bouton ajouter patient
    addPatientBtn.addEventListener("click", () => {
      patientForm.style.display = "flex";
      patientForm.reset();
      patientId.value = '';
    });

    cancelBtn.addEventListener("click", () => {
      patientForm.style.display = "none";
      patientForm.reset();
      patientId.value = '';
    });

    // Recherche
    let searchTimeout;
    search.addEventListener("input", () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(loadPatients, 300);
    });

    // Charger au démarrage
    loadPatients();
  </script>
</body>
</html>