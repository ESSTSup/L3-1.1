<?php
// ManageSubscription.php
session_start();
require_once 'SubscriptionManager.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$subscriptionManager = new SubscriptionManager();

// Récupérer l'abonnement actuel et les plans disponibles
$currentSubscription = $subscriptionManager->getCurrentSubscription($userId);
$allPlans = $subscriptionManager->getAllPlans();
$currentPlanName = $currentSubscription['plan_name'] ?? 'Free';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gérer l'abonnement</title>
  <link rel="stylesheet" href="ManageSubscription.css" />
  <style>
    .message {
      padding: 15px;
      margin: 20px 0;
      border-radius: 5px;
      display: none;
    }
    .message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .current-plan-badge {
      background: #4CAF50;
      color: white;
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 12px;
      margin-left: 10px;
    }
    .plan.disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <div class="subscription-container">
    <h2>Changer de plan</h2>
    
    <div id="messageBox" class="message"></div>

    <form id="subscriptionForm" class="subscription-form">
      <div class="plans">
        <?php foreach ($allPlans as $plan): ?>
          <?php 
            $isActive = ($plan['plan_name'] === $currentPlanName);
            $features = explode(',', $plan['features']);
          ?>
          <label class="plan <?= $isActive ? 'active' : '' ?>" id="<?= strtolower($plan['plan_name']) ?>Plan">
            <input 
              type="radio" 
              name="plan" 
              value="<?= htmlspecialchars($plan['plan_name']) ?>" 
              <?= $isActive ? 'checked' : '' ?>
            />
            <h3>
              <?= htmlspecialchars($plan['plan_name']) ?>
              <?php if ($isActive): ?>
                <span class="current-plan-badge">Plan actuel</span>
              <?php endif; ?>
            </h3>
            <?php if ($plan['price'] > 0): ?>
              <p class="price"><?= number_format($plan['price'], 2) ?> € / mois</p>
            <?php else: ?>
              <p class="price">Gratuit</p>
            <?php endif; ?>
            <ul>
              <?php foreach ($features as $feature): ?>
                <li>✔ <?= htmlspecialchars(trim($feature)) ?></li>
              <?php endforeach; ?>
            </ul>
          </label>
        <?php endforeach; ?>
      </div>

      <button type="submit" class="change-btn" id="submitBtn">Changer maintenant</button>
    </form>

    <div class="subscription-info" style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 5px;">
      <h3>Informations sur votre abonnement actuel</h3>
      <p><strong>Plan:</strong> <?= htmlspecialchars($currentPlanName) ?></p>
      <?php if (isset($currentSubscription['max_patients'])): ?>
        <p><strong>Patients maximum:</strong> <?= $currentSubscription['max_patients'] ?></p>
      <?php else: ?>
        <p><strong>Patients:</strong> Illimités</p>
      <?php endif; ?>
      <p><strong>Depuis:</strong> <?= date('d/m/Y', strtotime($currentSubscription['start_date'])) ?></p>
    </div>
  </div>

  <script>
    const form = document.getElementById("subscriptionForm");
    const messageBox = document.getElementById("messageBox");
    const submitBtn = document.getElementById("submitBtn");
    const currentPlan = "<?= $currentPlanName ?>";

    // Gérer le changement visuel des plans
    const plans = document.querySelectorAll('.plan');
    plans.forEach(plan => {
      const radio = plan.querySelector('input[type="radio"]');
      radio.addEventListener('change', () => {
        plans.forEach(p => p.classList.remove('active'));
        plan.classList.add('active');
      });
    });

    // Fonction pour afficher les messages
    function showMessage(message, type) {
      messageBox.textContent = message;
      messageBox.className = 'message ' + type;
      messageBox.style.display = 'block';
      
      setTimeout(() => {
        messageBox.style.display = 'none';
      }, 5000);
    }

    // Gérer la soumission du formulaire
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      
      const selectedPlan = document.querySelector('input[name="plan"]:checked').value;
      
      // Vérifier si c'est le même plan
      if (selectedPlan === currentPlan) {
        showMessage("Vous êtes déjà sur le plan " + selectedPlan, "error");
        return;
      }

      // Désactiver le bouton pendant le traitement
      submitBtn.disabled = true;
      submitBtn.textContent = "Changement en cours...";

      try {
        const formData = new FormData();
        formData.append('action', 'change_plan');
        formData.append('plan', selectedPlan);

        const response = await fetch('handle_subscription.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          showMessage(result.message, "success");
          
          // Recharger la page après 2 secondes pour refléter les changements
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        } else {
          showMessage(result.message, "error");
          submitBtn.disabled = false;
          submitBtn.textContent = "Changer maintenant";
        }
      } catch (error) {
        showMessage("Erreur de connexion. Veuillez réessayer.", "error");
        submitBtn.disabled = false;
        submitBtn.textContent = "Changer maintenant";
      }
    });

    // Charger les informations au démarrage
    window.addEventListener('DOMContentLoaded', async () => {
      try {
        const response = await fetch('handle_subscription.php?action=check_patient_limit');
        const result = await response.json();
        
        if (result.message) {
          console.log('Limite patients:', result.message);
        }
      } catch (error) {
        console.error('Erreur lors de la vérification de la limite:', error);
      }
    });
  </script>
</body>
</html>
