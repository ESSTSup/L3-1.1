<?php
// Inclure la configuration de la base de données
require_once 'db_config.php';

// Obtenir une connexion à la base de données
$db = DBConfig::getPDOConnection();

// Variables pour les rapports
$totalPatients = 0;
$consultations = 0;
$revenue = 0;
$satisfaction = 0;
$avgWaitTime = 0;
$appointmentsToday = 0;

// Obtenir les données de la base de données
try {
    // Récupérer le nombre total de patients
    $stmt = $db->query("SELECT COUNT(*) FROM patient");
    $totalPatients = $stmt->fetchColumn();

    // Récupérer le nombre de consultations
    $stmt = $db->query("SELECT COUNT(*) FROM consultation");
    $consultations = $stmt->fetchColumn();

    // Récupérer le revenu total (exemple avec la table patient_finance)
    $stmt = $db->query("SELECT SUM(total_amount) FROM patient_finance");
    $revenue = $stmt->fetchColumn();

    // Récupérer la satisfaction moyenne (exemple avec une valeur fixe ou calculée par rapport à la table des évaluations si elle existe)
    $satisfaction = 96; // Exemple fixe ou peut être récupéré d'une table

    // Récupérer le temps d'attente moyen (exemple avec la table des rendez-vous si nécessaire)
    $stmt = $db->query("SELECT AVG(TIMESTAMPDIFF(MINUTE, appointment_date, appointment_time)) FROM appointments");
    $avgWaitTime = $stmt->fetchColumn();

    // Récupérer le nombre de rendez-vous pour aujourd'hui
    $today = date('Y-m-d');
    $stmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date = :today");
    $stmt->execute([':today' => $today]);
    $appointmentsToday = $stmt->fetchColumn();
} catch (Exception $e) {
    echo "Erreur de récupération des données: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="report.css">
</head>
<body>

    <div class="report-container">

        <header>
            <h1>Reports</h1>
            <p>View and download your medical reports</p>

            <div class="filter">
                <label for="period">Select: </label>
                <select id="period" name="period">
                    <option value="month">Monthly</option>
                    <option value="week">Weekly</option>
                    <option value="year">Yearly</option>
                </select>
            </div>
        </header>

        <div class="report-card">
            <div class="info">
                <div class="icon"></div>
                <h3>Total Patients</h3>
                <p class="number"><?php echo $totalPatients; ?></p>
            </div>
            <button>VIEW</button>
        </div>

        <div class="report-card">
            <div class="info">
                <div class="icon"></div>
                <h3>Consultations</h3>
                <p class="number"><?php echo $consultations; ?></p>
            </div>
            <button>VIEW</button>
        </div>

        <div class="report-card">
            <div class="info">
                <div class="icon"></div>
                <h3>Revenue</h3>
                <p class="number"><?php echo number_format($revenue, 2); ?> <span class="currency">DZ</span></p>
            </div>
            <button>VIEW</button>
        </div>

        <div class="report-card">
            <div class="info">
                <div class="icon"></div>
                <h3>Satisfaction</h3>
                <p class="number"><?php echo $satisfaction; ?>%</p>
            </div>
            <button>VIEW</button>
        </div>

        <div class="report-card">
            <div class="info">
                <div class="icon"></div>
                <h3>Avg Wait Time</h3>
                <p class="number"><?php echo $avgWaitTime; ?> min</p>
            </div>
            <button>VIEW</button>
        </div>

        <div class="report-card">
            <div class="info">
                <div class="icon"></div>
                <h3>Today</h3>
                <p class="number"><?php echo $appointmentsToday; ?></p>
                <small>Appointment Overview</small>
            </div>
            <button>VIEW</button>
        </div>

    </div>

    <a href="Dash.html" class="back-link">← Back to Dashboard</a>

    <script>
        console.log("Reports page loaded.");

        document.getElementById("period").addEventListener("change", function () {
            console.log("Period selected:", this.value);
            alert("You selected: " + this.value);
        });

        const viewButtons = document.querySelectorAll(".report-card button");

        viewButtons.forEach((btn, index) => {
            btn.addEventListener("click", function () {
                const title = this.parentElement.querySelector("h3").textContent;
                alert("Opening report: " + title);
                console.log("VIEW clicked for:", title);
            });
        });
    </script>

</body>
</html>
