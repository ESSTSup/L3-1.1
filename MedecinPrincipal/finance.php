<?php
// Inclure la configuration de la base de données
include 'db_config.php';

// Récupérer les paiements et salaires depuis la base de données
header('Content-Type: application/json');

try {
    $db = getPDOConnection();
    
    // Requête pour obtenir tous les paiements et salaires
    $stmt = $db->prepare("SELECT * FROM assistant_finance");
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'data' => $payments]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch data: ' . $e->getMessage()]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Finance Management</title>
  <style>
    /* Style de base pour la page */
    body {
      font-family: "Segoe UI", sans-serif;
      background-color: #f5f6fa;
      margin: 0;
      padding: 30px;
    }

    h1 {
      text-align: center;
      color: #2b3a67;
      margin-bottom: 10px;
    }

    .subtitle {
      text-align: center;
      color: #555;
      margin-bottom: 30px;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 25px;
    }

    .summary {
      display: flex;
      justify-content: space-around;
      background: #f9fafb;
      border-radius: 10px;
      padding: 15px 10px;
      margin-bottom: 25px;
      text-align: center;
    }

    .summary div {
      font-size: 18px;
      color: #333;
    }

    .summary span {
      display: block;
      font-size: 20px;
      font-weight: bold;
      color: #2b3a67;
    }

    button {
      background: #545659;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 10px 16px;
      cursor: pointer;
      transition: 0.2s;
    }

    button:hover {
      background: #4056a1;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th,
    td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    th {
      background: #f0f0f0;
      color: #57595f;
    }

    .actions button {
      padding: 6px 10px;
      border-radius: 5px;
      font-size: 13px;
    }

    .edit {
      background: #f4b400;
    }

    .delete {
      background: #d9534f;
    }
    
    form {
      display: none;
      margin-top: 20px;
      background: #f9fafb;
      border-radius: 10px;
      border: 1px solid #ddd;
      padding: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }

    input,
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 10px;
    }

    .form-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
  </style>
</head>
<body>
  <h1>Finance Management</h1>
  <p class="subtitle">Track your payments, salaries, and profit</p>

  <div class="container">
    <div class="summary">
      <div>
        Patients Payments
        <span id="patientsTotal">0 DZD</span>
      </div>
      <div>
        Assistants Salaries
        <span id="assistantsTotal">0 DZD</span>
      </div>
      <div>
        Total Profit
        <span id="profitTotal">0 DZD</span>
      </div>
    </div>

    <div
      style="
        display: flex;
        justify-content: space-between;
        align-items: center;
      "
    >
      <h3>Transactions</h3>
      <button id="addPaymentBtn">Add Payment</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Name</th>
          <th>Amount (DZD)</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="paymentTableBody"></tbody>
    </table>

    <form id="paymentForm">
      <h3 id="formTitle">Add Payment</h3>
      <label>Type</label>
      <select id="type" required>
        <option value="">Select type</option>
        <option value="Patient">Patient</option>
        <option value="Assistant">Assistant</option>
      </select>

      <label>Name</label>
      <input type="text" id="name" required />

      <label>Amount (DZD)</label>
      <input type="number" id="amount" required />

      <label>Date</label>
      <input type="date" id="date" required />

      <div class="form-buttons">
        <button type="submit">Save</button>
        <button type="button" id="cancelBtn" style="background: #392d2d">
          Cancel
        </button>
      </div>
    </form>
  </div>

  <script>
    // Fonction pour charger les paiements depuis le serveur PHP
    function loadPayments() {
      fetch('finance.php') // Requête vers le fichier PHP
        .then(response => response.json())
        .then(data => {
          const payments = data.data;
          const paymentTableBody = document.getElementById('paymentTableBody');
          let patientSum = 0;
          let assistantSum = 0;

          payments.forEach(p => {
            if (p.type === 'Patient') patientSum += Number(p.amount);
            else if (p.type === 'Assistant') assistantSum += Number(p.amount);

            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${p.type}</td>
              <td>${p.name}</td>
              <td>${p.amount}</td>
              <td>${p.date}</td>
              <td class="actions">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
              </td>
            `;
            paymentTableBody.appendChild(row);
          });

          document.getElementById('patientsTotal').textContent = `${patientSum} DZD`;
          document.getElementById('assistantsTotal').textContent = `${assistantSum} DZD`;
          document.getElementById('profitTotal').textContent = `${patientSum - assistantSum} DZD`;
        });
    }

    loadPayments();
  </script>
</body>
</html>
