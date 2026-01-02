



 <?php
include 'db_config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['consultation_id'])) {
    echo json_encode(['success'=>false,'message'=>'Missing consultation_id']);
    exit;
}

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("
        UPDATE consultation SET 
            appointment_id=:appointment_id,
            pat_id=:pat_id,
            doc_id=:doc_id,
            clinic_id=:clinic_id,
            consultation_date=:consultation_date,
            consultation_time=:consultation_time,
            symptoms=:symptoms,
            diagnosis=:diagnosis,
            prescription=:prescription,
            notes=:notes,
            temperature=:temperature,
            blood_pressure=:blood_pressure,
            heart_rate=:heart_rate,
            weight=:weight,
            height=:height,
            status=:status,
            followup_needed=:followup_needed,
            followup_date=:followup_date,
            consultation_fee=:consultation_fee,
            is_paid=:is_paid
        WHERE consultation_id=:id
    ");

    $stmt->execute([
        ':id' => $data['consultation_id'],
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

    echo json_encode(['success'=>true]);

} catch (Exception $e) {
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
?>
