


<?php
// add_appointment.php
include 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['pat_id'], $data['doc_id'], $data['clinic_id'], $data['appointment_date'], $data['appointment_time'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("
        INSERT INTO appointments (pat_id, doc_id, clinic_id, appointment_date, appointment_time)
        VALUES (:pat_id, :doc_id, :clinic_id, :appointment_date, :appointment_time)
    ");

    $stmt->execute([
        ':pat_id' => $data['pat_id'],
        ':doc_id' => $data['doc_id'],
        ':clinic_id' => $data['clinic_id'],
        ':appointment_date' => $data['appointment_date'],
        ':appointment_time' => $data['appointment_time']
    ]);

    echo json_encode(['success' => true, 'appointment_id' => $pdo->lastInsertId()]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>






















 
