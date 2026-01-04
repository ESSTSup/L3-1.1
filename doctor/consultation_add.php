<?php
session_start();
require_once "../config/db_config.php";

$pdo = getPDOConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "INSERT INTO consultation (
        appointment_id, pat_id, doc_id, clinic_id,
        consultation_date, consultation_time,
        symptoms, diagnosis, prescription, notes,
        consultation_fee, status
    ) VALUES (
        ?, ?, ?, ?, CURDATE(), CURTIME(),
        ?, ?, ?, ?, ?, 'completed'
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['appointment_id'],
        $_POST['pat_id'],
        $_SESSION['doc_id'],
        $_POST['clinic_id'],
        $_POST['symptoms'],
        $_POST['diagnosis'],
        $_POST['prescription'],
        $_POST['notes'],
        $_POST['consultation_fee']
    ]);

    header("Location: consultations.php");
    exit;
}
?>

<h2>Ajouter une consultation</h2>

<form method="post">
    <input type="hidden" name="appointment_id" value="<?= $_GET['app_id'] ?>">
    <input type="hidden" name="pat_id" value="<?= $_GET['pat_id'] ?>">
    <input type="hidden" name="clinic_id" value="<?= $_GET['clinic_id'] ?>">

    Symptômes:<br>
    <textarea name="symptoms" required></textarea><br>

    Diagnostic:<br>
    <textarea name="diagnosis" required></textarea><br>

    Prescription:<br>
    <textarea name="prescription"></textarea><br>

    Notes:<br>
    <textarea name="notes"></textarea><br>

    Honoraires:<br>
    <input type="number" step="0.01" name="consultation_fee" required><br><br>

    <button type="submit">Enregistrer</button>
</form>
