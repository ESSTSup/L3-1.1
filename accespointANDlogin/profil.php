<?php
session_start();

require_once "../database/db_config.php";

/* SECURITY */
if (!isset($_SESSION['clinic_id'], $_SESSION['login_type'])) {
    header("Location: Login.php");
    exit;
}

$db        = DatabaseConfig::getPDOConnection();
$clinicId  = $_SESSION['clinic_id'];
   $loginType = $_SESSION['access_type']; // doctor | assistant


/* HANDLE PROFILE CLICK */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_user_id'])) {
    $_SESSION['selected_user_id'] = (int) $_POST['selected_user_id'];
    http_response_code(200);
    exit;
}

/* =========================
   FETCH DATA (STRICT LOGIC)
========================= */

$doctors = [];
$assistants = [];

/* DOCTOR LOGIN → SHOW DOCTORS */
if ($loginType === 'doctor') {
    $stmt = $db->prepare(
        "SELECT doc_id, doc_name, doc_lname
         FROM doctor
         WHERE clinic_id = ?"
    );
    $stmt->execute([$clinicId]);
    $doctors = $stmt->fetchAll();
}

/* ASSISTANT LOGIN → SHOW ASSISTANTS */
if ($loginType === 'assistant') {
    $stmt = $db->prepare(
        "SELECT assis_id, assis_name, assis_lname
         FROM assistant"
    );
    $stmt->execute();
    $assistants = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Équipe Médicale</title>
<link rel="stylesheet" href="style.css">
<script defer src="script.js"></script>
</head>

<body>

<header>
  <h1>Équipe Médicale</h1>
</header>

<main class="profiles">

<?php if ($loginType === 'doctor'): ?>
<!-- ========== DOCTORS ONLY ========== -->



<h2>Médecins</h2>

<div class="grid">
<?php foreach ($doctors as $doc): ?>

<?php
$photoMap = [
  'MOUFOUKI' => 'moufouki.jpg',
  'MEKLATI' => 'meklati.jpg',
  'LACHI'   => 'lachi.jpg',
  'HELLAL'  => 'hellal.jpg',
 'YASMINE' => 'yasmine.jpg',

  'MAJED'   => 'majed.jpg',
];

$lastName = strtoupper(trim($doc['doc_name']));
$photo = $photoMap[$lastName] ?? 'default.jpg';
?>

<div class="card" onclick="selectUser(<?= $doc['doc_id'] ?>)">
  <img src="../img/team/<?= $photo ?>" alt="<?= htmlspecialchars($lastName) ?>">
  <p>
    Dr <?= htmlspecialchars($doc['doc_name']) ?>
  </p>
</div>


<?php endforeach; ?>
</div>

<?php endif; ?>

<?php if ($loginType === 'assistant'): ?>
<!-- ========== ASSISTANTS ONLY ========== -->
<section>
  <h2>Assistants</h2>
  <div class="grid">

    <?php if (empty($assistants)): ?>
        <p>Aucun assistant trouvé.</p>
    <?php else: ?>

        <?php foreach ($assistants as $a): ?>
<?php
$assistantPhotos = [
  'SLIMANI'    => 'SLIMANI.jpg',
  'BERRAHMEN' => 'BERRAHMEN.jpg',
  'MAJED'     => 'MAJED.jpg',
];

$lastName = strtoupper(trim($a['assis_name']));
$photo = $assistantPhotos[$lastName] ?? 'default.jpg';
?>

<div class="card" onclick="selectUser(<?= $a['assis_id'] ?>)">
  <img src="../img/team/<?= $photo ?>" alt="<?= htmlspecialchars($lastName) ?>">
  <p><?= htmlspecialchars($a['assis_name']) ?></p>
</div>


        <?php endforeach; ?>

    <?php endif; ?>



  </div>
</section>

<?php endif; ?>

</main>

</body>
</html>
