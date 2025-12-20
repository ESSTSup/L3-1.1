<?php 
session_start();
require_once "../database/db_config.php";

/*
|--------------------------------------------------------------------------
| HARD FIX: block access if profile not selected (staff only)
|--------------------------------------------------------------------------
| If doctor/assistant comes here WITHOUT selecting a profile,
| we force them back. Otherwise login will ALWAYS fail.
*/
if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' &&
    !isset($_SESSION['selected_user_id']) &&
    isset($_SESSION['login_type']) &&
    $_SESSION['login_type'] !== 'patient'
) {
    header("Location: profil.php");
    exit;

}

// ================= AJAX LOGIN =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        echo json_encode(["success" => false, "message" => "Champs manquants"]);
        exit;
    }

    $db = Database::getInstance()->getConnection();

    /*
    |--------------------------------------------------------------------------
    | PATIENT LOGIN (NO PROFILE)
    |--------------------------------------------------------------------------
    */ // ======================
// CASE: PATIENT
// ======================
// ======================
// PATIENT LOGIN (CLEAN & FINAL)
// ======================
if (
    isset($_SESSION['login_type']) &&
    $_SESSION['login_type'] === 'patient'
) {
 $stmt = $db->prepare(
    "SELECT * FROM patient WHERE pat_email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $pat = $res->fetch_assoc();

    // 1️⃣ If password already hashed
    if (password_verify($password, $pat['pat_password'])) {
        $loginOK = true;
    }
    // 2️⃣ If password still plain text (first login)
    elseif ($password === $pat['pat_password']) {

        $newHash = password_hash($password, PASSWORD_DEFAULT);

        $upd = $db->prepare(
            "UPDATE patient SET pat_password = ? WHERE pat_id = ?"
        );
        $upd->bind_param("si", $newHash, $pat['pat_id']);
        $upd->execute();

        $loginOK = true;
    } else {
        $loginOK = false;
    }

    if ($loginOK) {
        $_SESSION['user_id'] = $pat['pat_id'];
        $_SESSION['role']    = 'patient';

        echo json_encode([
            "success"  => true,
            "redirect" => "../patient/dashboard.html"
        ]);
        exit;
    }


    }

    echo json_encode([
        "success" => false,
        "message" => "Email ou mot de passe incorrect"
    ]);
    exit;
}



    /*
    |--------------------------------------------------------------------------
    | STAFF LOGIN (PROFILE REQUIRED)
    |--------------------------------------------------------------------------
    */
    $selectedId = (int) $_SESSION['selected_user_id'];

    // ---------- DOCTOR ----------
    $stmt = $db->prepare(
        "SELECT * FROM doctor WHERE doc_id = ? AND doc_email = ?"
    );
    $stmt->bind_param("is", $selectedId, $email);
    $stmt->execute();
    $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $doc = $res->fetch_assoc();

    // 1️⃣ If password already hashed
    if (password_verify($password, $doc['doc_password'])) {
        $loginOK = true;
    }
    // 2️⃣ If password still plain (first login only)
    elseif ($password === $doc['doc_password']) {

        $newHash = password_hash($password, PASSWORD_DEFAULT);

        $upd = $db->prepare(
            "UPDATE doctor SET doc_password = ? WHERE doc_id = ?"
        );
        $upd->bind_param("si", $newHash, $doc['doc_id']);
        $upd->execute();

        $loginOK = true;
    } else {
        $loginOK = false;
    }

    if ($loginOK) {
        $_SESSION['user_id'] = $doc['doc_id'];
        $_SESSION['role']    = 'doctor';

        echo json_encode([
            "success"  => true,
            "redirect" => ($doc['doc_role'] === 'admin')
                ? "../MedecinPrincipal/Dash.html"
                : "../doctor/dashboard.html"
        ]);
        exit;
    }
}


    // ---------- ASSISTANT ----------
    $stmt = $db->prepare(
        "SELECT * FROM assistant WHERE assis_id = ? AND assis_email = ?"
    );
    $stmt->bind_param("is", $selectedId, $email);
    $stmt->execute();
    $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $assis = $res->fetch_assoc();

    // 1️⃣ If password already hashed
    if (password_verify($password, $assis['assis_password'])) {
        $loginOK = true;
    }
    // 2️⃣ If password still plain (first login only)
    elseif ($password === $assis['assis_password']) {

        $newHash = password_hash($password, PASSWORD_DEFAULT);

        $upd = $db->prepare(
            "UPDATE assistant SET assis_password = ? WHERE assis_id = ?"
        );
        $upd->bind_param("si", $newHash, $assis['assis_id']);
        $upd->execute();

        $loginOK = true;
    } else {
        $loginOK = false;
    }

    if ($loginOK) {
        $_SESSION['user_id'] = $assis['assis_id'];
        $_SESSION['role']    = 'assistant';

        echo json_encode([
            "success"  => true,
            "redirect" => "../assistant/assistandeshb.html"
        ]);
        exit;
    }
}


    echo json_encode(["success" => false, "message" => "Identifiants invalides"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<link rel="stylesheet" href="style.css">
<script defer src="script.js"></script>
</head>

<body>
<div class="login-container">
    <h1>Connexion</h1>
    <p id="error" style="color:red;"></p>

    <form id="loginForm">
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>
