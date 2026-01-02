


<?php
include 'db_config.php';
header('Content-Type: application/json');

try {
    $pdo = getPDOConnection();

    // Join waiting_list with patients to get names and ages
    $stmt = $pdo->query("
        SELECT w.waiting_id, w.appointment_id, w.clinic_id, w.checkin_time, w.status, w.pat_id,
               p.nom, p.prenom, p.age, a.reason
        FROM waiting_list w
        INNER JOIN patients p ON w.pat_id = p.pat_id
        LEFT JOIN appointments a ON w.appointment_id = a.appointment_id
        ORDER BY w.checkin_time ASC
    ");

    $patients = $stmt->fetchAll();
    $formatted = array_map(function($p){
        return [
            'id' => $p['waiting_id'],
            'name' => $p['prenom'] . ' ' . $p['nom'],
            'age' => $p['age'],
            'time' => date('H:i', strtotime($p['checkin_time'])),
            'status' => $p['status'],
            'appointment_id' => $p['appointment_id'],
            'clinic_id' => $p['clinic_id'],
            'pat_id' => $p['pat_id'],
            'reason' => $p['reason'] ?? 'Consultation'
        ];
    }, $patients);

    echo json_encode(['success' => true, 'patients' => $formatted]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>



















 
