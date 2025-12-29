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
    // ONLY MAJED is a man (doc_id = 7)
    if ((int)$doc['doc_id'] === 7) {
        $gender = 'men';
        $photoId = 45;
    } else {
        $gender = 'women';
        // stable photo per doctor
        $photoId = ($doc['doc_id'] % 90) + 1;
    }
  ?>

  <div class="card" onclick="selectUser(<?= $doc['doc_id'] ?>)">
    <img src="https://randomuser.me/api/portraits/<?= $gender ?>/<?= $photoId ?>.jpg">
    <p>
      Dr <?= htmlspecialchars($doc['doc_name']) ?>
      <?= htmlspecialchars($doc['doc_lname']) ?>
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
            <div class="card" onclick="selectUser(<?= $a['assis_id'] ?>)">
                <img src="https://randomuser.me/api/portraits/women/40.jpg">
                <p>
                  <?= htmlspecialchars($a['assis_name']) ?>
                  <?= htmlspecialchars($a['assis_lname']) ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

  </div>
</section>

<?php endif; ?>

</main>

</body>
</html>
