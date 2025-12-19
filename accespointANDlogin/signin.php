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
    */
    if (!isset($_SESSION['selected_user_id'])) {

        $stmt = $db->prepare("SELECT * FROM patient WHERE pat_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $pat = $res->fetch_assoc();

            if ($password === $pat['pat_password']) {
                $_SESSION['user_id'] = $pat['pat_id'];
                $_SESSION['role']    = 'patient';

                echo json_encode([
                    "success"  => true,
                    "redirect" => "../patient/dashboard.php"
                ]);
                exit;
            }
        }

        echo json_encode(["success" => false, "message" => "Identifiants invalides"]);
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

        if ($password === $doc['doc_password']) {
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

        if ($password === $assis['assis_password']) {
            $_SESSION['user_id'] = $assis['assis_id'];
            $_SESSION['role']    = 'assistant';

            echo json_encode([
                "success"  => true,
                "redirect" => "../assistant/assistandeshb.php"
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
