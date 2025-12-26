<?php
// Démarrer la session pour accéder aux variables de session
session_start();

// Inclure la configuration de la base de données
require_once 'db_config.php';

// Récupérer les rendez-vous selon le rôle
function getAppointmentsByRole($role) {
    $pdo = getPDOConnection();
    
    $sql = "";
    if ($role == 'patient') {
        $sql = "SELECT a.appointment_id, p.pat_name AS patient, d.doc_name AS doctor, a.appointment_date, a.appointment_time, a.status
                FROM appointments a
                JOIN patient p ON a.pat_id = p.pat_id
                JOIN doctor d ON a.doc_id = d.doc_id
                WHERE a.pat_id = :pat_id";
    } elseif ($role == 'doctor') {
        $sql = "SELECT a.appointment_id, p.pat_name AS patient, d.doc_name AS doctor, a.appointment_date, a.appointment_time, a.status
                FROM appointments a
                JOIN patient p ON a.pat_id = p.pat_id
                JOIN doctor d ON a.doc_id = d.doc_id
                WHERE a.doc_id = :doc_id";
    } elseif ($role == 'assistant') {
        $sql = "SELECT a.appointment_id, p.pat_name AS patient, d.doc_name AS doctor, a.appointment_date, a.appointment_time, a.status
                FROM appointments a
                JOIN patient p ON a.pat_id = p.pat_id
                JOIN doctor d ON a.doc_id = d.doc_id";
    }

    $stmt = $pdo->prepare($sql);
    
    // Bind les paramètres selon le rôle
    if ($role == 'patient') {
        if (isset($_SESSION['pat_id'])) {
            $stmt->bindParam(':pat_id', $_SESSION['pat_id']); // ID patient stocké dans la session
        } else {
            echo "ID du patient non défini dans la session.";
            return [];
        }
    } elseif ($role == 'doctor') {
        if (isset($_SESSION['doc_id'])) {
            $stmt->bindParam(':doc_id', $_SESSION['doc_id']); // ID docteur stocké dans la session
        } else {
            echo "ID du docteur non défini dans la session.";
            return [];
        }
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exporter les rendez-vous en CSV
function exportAppointmentsToCSV($role) {
    $appointments = getAppointmentsByRole($role);

    if (empty($appointments)) {
        echo "Aucune donnée à exporter.";
        return;
    }

    $filename = "appointments-$role.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ["ID", "Patient", "Docteur", "Date", "Heure", "Statut"]);

    foreach ($appointments as $appointment) {
        fputcsv($output, $appointment);
    }

    fclose($output);
    exit();
}

// Vérifier si l'utilisateur veut exporter les données
if (isset($_GET['export'])) {
    exportAppointmentsToCSV($_GET['role']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rendez-vous</title>
    <link rel="stylesheet" href="manageApp.css">
</head>
<body>

<header>
    <h1>Gestion des Rendez-vous</h1>
</header>

<main>
    <section class="role-selector">
        <form method="get" action="appointments.php">
            <label for="role">Choisir le rôle :</label>
            <select id="role" name="role">
                <option value="patient">Patient</option>
                <option value="doctor">Docteur</option>
                <option value="assistant">Assistant</option>
            </select>
            <button type="submit">Afficher</button>
        </form>
    </section>

    <section class="appointments">
        <h2>Liste des Rendez-vous</h2>
        <table id="appointments-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Docteur</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Vérifier si le rôle est sélectionné
                if (isset($_GET['role'])) {
                    $role = $_GET['role'];
                    $appointments = getAppointmentsByRole($role);

                    if (empty($appointments)) {
                        echo '<tr><td colspan="6" style="text-align: center; padding: 1rem;">Aucun rendez-vous trouvé pour ce rôle.</td></tr>';
                    } else {
                        foreach ($appointments as $app) {
                            echo '<tr>';
                            echo '<td>' . $app['appointment_id'] . '</td>';
                            echo '<td>' . $app['patient'] . '</td>';
                            echo '<td>' . $app['doctor'] . '</td>';
                            echo '<td>' . $app['appointment_date'] . '</td>';
                            echo '<td>' . $app['appointment_time'] . '</td>';
                            echo '<td class="' . strtolower($app['status']) . '">' . $app['status'] . '</td>';
                            echo '</tr>';
                        }
                    }
                }
                ?>
            </tbody>
        </table>

        <!-- Boutons d'actions -->
        <div style="margin-top: 10px;">
            <a href="appointments.php?role=<?php echo $role; ?>&export=true" class="export-button">Exporter en CSV</a>
        </div>
    </section>

    <a href="Dash.html" class="back-button">← Retour au Dashboard</a>
</main>

</body>
</html>

