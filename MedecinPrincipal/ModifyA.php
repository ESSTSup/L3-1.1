<?php
// assistants-list.php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['doc_id']) && !isset($_SESSION['clinic_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'AssistantManager.php';
$assistantManager = new AssistantManager();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des Assistants</title>
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

    .container {
      max-width: 1200px;
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
      text-decoration: none;
      display: inline-block;
    }

    .top-bar button:hover, .top-bar a:hover {
      background: #1a1a1d;
    }

    .assistant-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 15px;
    }

    .assistant-card {
      background: #fafafa;
      border-radius: 10px;
      border: 1px solid #ddd;
      padding: 15px;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    .assistant-header {
      font-size: 18px;
      font-weight: bold;
      color: #6e7485;
      margin-bottom: 10px;
    }

    .assistant-info {
      margin: 10px 0;
      color: #333;
      line-height: 1.6;
    }

    .assistant-doctors {
      font-size: 13px;
      color: #666;
      font-style: italic;
      margin-top: 10px;
    }

    .actions {
      margin-top: 15px;
      display: flex;
      justify-content: flex-end;
      gap: 8px;
    }

    .actions button, .actions a {
      border: none;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
      color: white;
      text-decoration: none;
      display: inline-block;
    }

    .edit {
      background: #f4b400;
    }

    .delete {
      background: #d9534f;
    }

    .message {
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 6px;
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

    .stats {
      text-align: center;
      margin-bottom: 20px;
      padding: 15px;
      background: #e3f2fd;
      border-radius: 8px;
      color: #1565c0;
    }
  </style>
</head>
<body>
  <a href="dash.html" class="back-btn">← Retour au tableau de bord</a>

  <h1>Gestion des Assistants</h1>
  <p class="subtitle">Gérez votre équipe d'assistants médicaux</p>

  <div class="container">
    <div id="messageBox" class="message"></div>

    <div class="stats" id="stats">
      <strong>Chargement...</strong>
    </div>

    <div class="top-bar">
      <input
        type="text"
        id="search"
        placeholder="🔍 Rechercher par nom ou email..."
      />
      <a href="ModifyAssistant.php" style="background: #4e515a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">
        Ajouter un Assistant
      </a>
    </div>

    <div id="assistantList" class="assistant-list">
      <div class="loading">Chargement des assistants...</div>
    </div>
  </div>

  <script>
    const assistantList = document.getElementById('assistantList');
    const search = document.getElementById('search');
    const messageBox = document.getElementById('messageBox');
    const stats = document.getElementById('stats');

    // Fonction pour afficher les messages
    function showMessage(message, type) {
      messageBox.textContent = message;
      messageBox.className = 'message ' + type;
      messageBox.style.display = 'block';
      
      setTimeout(() => {
        messageBox.style.display = 'none';
      }, 5000);
    }

    // Charger les assistants
    async function loadAssistants() {
      try {
        const searchQuery = search.value;
        const response = await fetch(`handle_assistant.php?action=get_all&search=${encodeURIComponent(searchQuery)}`);
        const result = await response.json();

        if (result.success) {
          assistantList.innerHTML = "";
          
          if (result.assistants.length === 0) {
            assistantList.innerHTML = '<div class="loading">Aucun assistant trouvé</div>';
            stats.innerHTML = '<strong>Total: 0 assistant</strong>';
            return;
          }

          stats.innerHTML = `<strong>Total: ${result.assistants.length} assistant(s)</strong>`;

          result.assistants.forEach(a => {
            const fullName = `${a.assis_name} ${a.assis_lname || ''}`.trim();
            const doctors = a.doctors || 'Aucun docteur assigné';
            
            const card = document.createElement("div");
            card.className = "assistant-card";
            card.innerHTML = `
              <div>
                <div class="assistant-header">${fullName}</div>
                <div class="assistant-info">
                  ✉️ ${a.assis_email}
                </div>
                <div class="assistant-doctors">
                  👨‍⚕️ Supervise: ${doctors}
                </div>
              </div>
              <div class="actions">
                <a href="ModifyAssistant.php?id=${a.assis_id}" class="edit">Modifier</a>
                <button class="delete" onclick="deleteAssistant(${a.assis_id}, '${fullName}')">Supprimer</button>
              </div>
            `;
            assistantList.appendChild(card);
          });
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur de chargement des assistants', 'error');
        console.error(error);
      }
    }

    // Supprimer un assistant
    async function deleteAssistant(id, name) {
      if (!confirm(`Êtes-vous sûr de vouloir supprimer ${name} ?`)) return;

      const formData = new FormData();
      formData.append('action', 'delete');
      formData.append('assistant_id', id);

      try {
        const response = await fetch('handle_assistant.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();

        if (result.success) {
          showMessage(result.message, 'success');
          loadAssistants();
        } else {
          showMessage(result.message, 'error');
        }
      } catch (error) {
        showMessage('Erreur lors de la suppression', 'error');
      }
    }

    // Recherche
    let searchTimeout;
    search.addEventListener('input', () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(loadAssistants, 300);
    });

    // Charger au démarrage
    loadAssistants();
  </script>
</body>
</html>