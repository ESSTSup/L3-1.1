<?php
// get_appointments.php
include 'db_config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDOConnection();

    $stmt = $pdo->query("
        SELECT a.appointment_id, a.pat_id, a.doc_id, a.clinic_id, 
               a.appointment_date, a.appointment_time, a.status,
               a.requested_at, a.confirmed_at, a.rejected_at
        FROM appointments a
        ORDER BY a.appointment_date ASC, a.appointment_time ASC
    ");
    
    $appointments = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'appointments' => $appointments
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
                                                                                                                                      
