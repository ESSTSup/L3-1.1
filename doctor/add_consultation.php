
<?php
include 'db_config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$required = ['appointment_id','pat_id','doc_id','clinic_id','consultation_date','consultation_time'];
foreach ($required as $r) {
    if (!isset($data[$r])) {
        echo json_encode(['success'=>false,'message'=>"Missing $r"]);
        exit;
    }
}

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("
        INSERT INTO consultation 
        (appointment_id, pat_id, doc_id, clinic_id, consultation_date, consultation_time, symptoms, diagnosis, prescription, notes, temperature, blood_pressure, heart_rate, weight, height, status, followup_needed, followup_date, consultation_fee, is_paid)
        VALUES 
        (:appointment_id, :pat_id, :doc_id, :clinic_id, :consultation_date, :consultation_time, :symptoms, :diagnosis, :prescription, :notes, :temperature, :blood_pressure, :heart_rate, :weight, :height, :status, :followup_needed, :followup_date, :consultation_fee, :is_paid)
    ");

    $stmt->execute([
        ':appointment_id' => $data['appointment_id'],
        ':pat_id' => $data['pat_id'],
        ':doc_id' => $data['doc_id'],
        ':clinic_id' => $data['clinic_id'],
        ':consultation_date' => $data['consultation_date'],
        ':consultation_time' => $data['consultation_time'],
        ':symptoms' => $data['symptoms'] ?? null,
        ':diagnosis' => $data['diagnosis'] ?? null,
        ':prescription' => $data['prescription'] ?? null,
        ':notes' => $data['notes'] ?? null,
        ':temperature' => $data['temperature'] ?? null,
        ':blood_pressure' => $data['blood_pressure'] ?? null,
        ':heart_rate' => $data['heart_rate'] ?? null,
        ':weight' => $data['weight'] ?? null,
        ':height' => $data['height'] ?? null,
        ':status' => $data['status'] ?? 'scheduled',
        ':followup_needed' => $data['followup_needed'] ?? 0,
        ':followup_date' => $data['followup_date'] ?? null,
        ':consultation_fee' => $data['consultation_fee'] ?? null,
        ':is_paid' => $data['is_paid'] ?? 0
    ]);

    echo json_encode(['success'=>true, 'consultation_id'=>$pdo->lastInsertId()]);

} catch (Exception $e) {
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
?>
















 
