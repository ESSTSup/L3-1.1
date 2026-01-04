<?php
session_start();
require_once "../config/db_config.php";

$pdo = getPDOConnection();
$doc_id = $_SESSION['doc_id'];

$sql = "
SELECT a.appointment_id,
       a.appointment_date,
       a.appointment_time,
       a.status,
       p.pat_name,
       p.pat_lname,
       c.clinic_name
FROM appointments a
JOIN patient p ON a.pat_id = p.pat_id
JOIN clinics c ON a.clinic_id = c.clinic_id
WHERE a.doc_id = ?
ORDER BY a.appointment_date, a.appointment_time
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$doc_id]);
$appointments = $stmt->fetchAll();
?>

<h2>Mes rendez-vous</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Patient</th>
    <th>Date</th>
    <th>Heure</th>
    <th>Clinique</th>
    <th>Statut</th>
    <th>Action</th>
</tr>

<?php foreach ($appointments as $a): ?>
<tr>
    <td><?= $a['pat_name'].' '.$a['pat_lname'] ?></td>
    <td><?= $a['appointment_date'] ?></td>
    <td><?= $a['appointment_time'] ?></td>
    <td><?= $a['clinic_name'] ?></td>
    <td><?= $a['status'] ?></td>
    <td>
        <a href="appointment_update.php?id=<?= $a['appointment_id'] ?>">Gérer</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
