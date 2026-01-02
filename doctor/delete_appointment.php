



<?php
// delete_appointment.php
include 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['appointment_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de rendez-vous manquant']);
    exit;
}

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = :id");
    $stmt->execute([':id' => $data['appointment_id']]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>




















 
