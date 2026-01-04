<?php
session_start();
require_once "../database/db_config.php";

if (!isset($_SESSION['register'])) {
    header("Location: personalInformation.php");
    exit;
}

$data = $_SESSION['register'];

$required = [
    'first_name',
    'last_name',
    'birth_date',
    'gender',
    'phone',
    'email',
    'password',
    'emergency_name',
    'emergency_phone'
];

foreach ($required as $key) {
    if (empty($data[$key])) {
        die("Missing required data: $key");
    }
}

$db = DatabaseConfig::getPDOConnection();

try {
    $db->beginTransaction();

    // prevent duplicate email
    $check = $db->prepare(
        "SELECT pat_id FROM patient WHERE pat_email = ?"
    );
    $check->execute([$data['email']]);
    if ($check->fetch()) {
        throw new Exception("Email already registered");
    }

    // insert patient
    $stmt = $db->prepare(
        "INSERT INTO patient
        (pat_name, pat_lname, pat_birthday, pat_gender, telephone, pat_email, pat_password)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $data['first_name'],
        $data['last_name'],
        $data['birth_date'],
        $data['gender'],
        $data['phone'],
        $data['email'],
        $data['password']
    ]);

    $patId = $db->lastInsertId();

    // insert medical info
    $stmt = $db->prepare(
        "INSERT INTO patient_medical
        (pat_id, allergies, chronic_diseases, emergency_name, emergency_phone)
        VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $patId,
        $data['allergies'] ?? null,
        $data['chronic'] ?? null,
        $data['emergency_name'],
        $data['emergency_phone']
    ]);

    $db->commit();

} catch (Exception $e) {
    $db->rollBack();
    die("Registration failed: " . $e->getMessage());
}

// auto-login
$_SESSION['user_id'] = $patId;
$_SESSION['role']    = 'patient';

unset($_SESSION['register']);

header("Location: ../patient/dashboard.html");
exit;
