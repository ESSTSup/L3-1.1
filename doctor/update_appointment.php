<?php
// update_appointment.php
include 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['appointment_id'], $data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

$validStatuses = ['requested','confirmed','rejected','cancelled','completed'];
if (!in_array($data['status'], $validStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Status invalide']);
    exit;
}

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("
        UPDATE appointments SET status = :status
        WHERE appointment_id = :id
    ");
    $stmt->execute([
        ':status' => $data['status'],
        ':id' => $data['appointment_id']
    ]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
