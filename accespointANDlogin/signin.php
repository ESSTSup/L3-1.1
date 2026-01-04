<?php
session_start();
require_once "../database/db_config.php";

/* =========================
   AJAX LOGIN HANDLER ONLY
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {

    header('Content-Type: application/json');

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        echo json_encode(["success" => false, "message" => "Champs manquants"]);
        exit;
    }

    $db = DatabaseConfig::getPDOConnection();

    /* ---------- PATIENT ---------- */
    if (($_SESSION['login_type'] ?? '') === 'patient') {

        $stmt = $db->prepare(
            "SELECT pat_id, pat_password FROM patient WHERE pat_email = ?"
        );
        $stmt->execute([$email]);
        $pat = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pat) {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
            exit;
        }

        $valid =
            password_verify($password, $pat['pat_password']) ||
            $password === $pat['pat_password'];

        if (!$valid) {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
            exit;
        }

        // migrate plaintext if needed
        if ($password === $pat['pat_password']) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db->prepare(
                "UPDATE patient SET pat_password = ? WHERE pat_id = ?"
            )->execute([$hash, $pat['pat_id']]);
        }

        $_SESSION['user_id'] = $pat['pat_id'];
        $_SESSION['role']    = 'patient';

        echo json_encode([
            "success"  => true,
            "redirect" => "../patient/dashboard.html"
        ]);
        exit;
    }

    /* ---------- STAFF ---------- */
    if (!isset($_SESSION['selected_user_id'])) {
        echo json_encode(["success" => false, "message" => "Profil non sélectionné"]);
        exit;
    }

    $id = (int) $_SESSION['selected_user_id'];

    /* DOCTOR */
    $stmt = $db->prepare(
        "SELECT doc_id, doc_password, doc_role
         FROM doctor
         WHERE doc_id = ? AND doc_email = ?"
    );
    $stmt->execute([$id, $email]);
    $doc = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($doc) {
        $valid =
            password_verify($password, $doc['doc_password']) ||
            $password === $doc['doc_password'];

        if ($valid) {
            if ($password === $doc['doc_password']) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $db->prepare(
                    "UPDATE doctor SET doc_password = ? WHERE doc_id = ?"
                )->execute([$hash, $doc['doc_id']]);
            }

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

    /* ASSISTANT */
    $stmt = $db->prepare(
        "SELECT assis_id, assis_password
         FROM assistant
         WHERE assis_id = ? AND assis_email = ?"
    );
    $stmt->execute([$id, $email]);
    $a = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($a) {
        $valid =
            password_verify($password, $a['assis_password']) ||
            $password === $a['assis_password'];

        if ($valid) {
            if ($password === $a['assis_password']) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $db->prepare(
                    "UPDATE assistant SET assis_password = ? WHERE assis_id = ?"
                )->execute([$hash, $a['assis_id']]);
            }

            $_SESSION['user_id'] = $a['assis_id'];
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

<body class="login-page">

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
