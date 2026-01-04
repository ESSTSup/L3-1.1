<?php
session_start();
require_once "../config/db_config.php";

$pdo = getPDOConnection();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    $sql = "UPDATE appointments
            SET status = ?, confirmed_at = NOW()
            WHERE appointment_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $id]);

    header("Location: appointments.php");
    exit;
}
?>

<h2>Mettre à jour le rendez-vous</h2>

<form method="post">
    <select name="status" required>
        <option value="confirmed">Confirmé</option>
        <option value="cancelled">Annulé</option>
        <option value="completed">Terminé</option>
    </select>
    <button type="submit">Enregistrer</button>
</form>
