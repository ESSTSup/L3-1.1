<?php
include 'db_config.php';
header('Content-Type: application/json');

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->query("
        SELECT c.*, p.nom AS pat_nom, p.prenom AS pat_prenom, p.email AS pat_email, p.telephone AS pat_tel
        FROM consultation c
        INNER JOIN patients p ON c.pat_id = p.pat_id
        ORDER BY c.consultation_date DESC, c.consultation_time DESC
    ");

    $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group by patient
    $patients = [];
    foreach ($consultations as $c) {
        $pat_id = $c['pat_id'];
        if (!isset($patients[$pat_id])) {
            $patients[$pat_id] = [
                'id' => $pat_id,
                'nom' => $c['pat_nom'],
                'prenom' => $c['pat_prenom'],
                'email' => $c['pat_email'],
                'telephone' => $c['pat_tel'],
                'consultations' => []
            ];
        }
        $patients[$pat_id]['consultations'][] = [
            'id' => $c['consultation_id'],
            'appointment_id' => $c['appointment_id'],
            'doc_id' => $c['doc_id'],
            'clinic_id' => $c['clinic_id'],
            'date' => $c['consultation_date'],
            'time' => $c['consultation_time'],
            'symptoms' => $c['symptoms'],
            'diagnosis' => $c['diagnosis'],
            'prescription' => $c['prescription'],
            'notes' => $c['notes'],
            'temperature' => $c['temperature'],
            'blood_pressure' => $c['blood_pressure'],
            'heart_rate' => $c['heart_rate'],
            'weight' => $c['weight'],
            'height' => $c['height'],
            'status' => $c['status'],
            'followup_needed' => $c['followup_needed'],
            'followup_date' => $c['followup_date'],
            'consultation_fee' => $c['consultation_fee'],
            'is_paid' => $c['is_paid']
        ];
    }

    echo json_encode(['success' => true, 'patients' => array_values($patients)]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
