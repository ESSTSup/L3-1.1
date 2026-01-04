<?php
session_start();
require_once "../config/db_config.php";

$pdo = getPDOConnection();
$doc_id = $_SESSION['doc_id'];

$sql = "
SELECT c.consultation_date,
       c.diagnosis,
       c.consultation_fee,
       p.pat_name,
       p.pat_lname
FROM consultation c
JOIN patient p ON c.pat_id = p.pat_id
WHERE c.doc_id = ?
ORDER BY c.consultation_date DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$doc_id]);
$consultations = $stmt->fetchAll();
?>

<h2>Mes consultations</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Patient</th>
    <th>Date</th>
    <th>Diagnostic</th>
    <th>Frais</th>
</tr>

<?php foreach ($consultations as $c): ?>
<tr>
    <td><?= $c['pat_name'].' '.$c['pat_lname'] ?></td>
    <td><?= $c['consultation_date'] ?></td>
    <td><?= $c['diagnosis'] ?></td>
    <td><?= $c['consultation_fee'] ?></td>
</tr>
<?php endforeach; ?>
</table>
