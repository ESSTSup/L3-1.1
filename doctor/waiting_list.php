

 <?php
session_start();
require_once "../config/db_config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$pdo = getPDOConnection();

// ⚠️ TEST (à enlever plus tard)
$_SESSION['doc_id'] = 1;
$doc_id = $_SESSION['doc_id'];

// Récupérer les patients depuis waiting_list + appointment + patient
$sql = "
SELECT 
    wl.waiting_id,
    wl.status,
    a.appointment_time,
    p.pat_name,
    p.pat_lname,
    p.pat_birthday
FROM waiting_list wl
JOIN appointments a ON wl.appointment_id = a.appointment_id
JOIN patient p ON wl.pat_id = p.pat_id
WHERE a.doc_id = ?
ORDER BY a.appointment_time
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$doc_id]);
$patients = $stmt->fetchAll();
?>
