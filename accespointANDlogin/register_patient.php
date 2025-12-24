<?php
session_start();
require_once __DIR__ . '/../database/db_config.php';

/*  Block direct access */
if (!isset($_SESSION['register'])) {
    header("Location: personalInformation.php");
    exit;
}        

$data = $_SESSION['register'];

/*  Required data validation */
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

/*  DB + error reporting */
$db = Database::getInstance()->getConnection();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/* Transaction start */
$db->begin_transaction();

try {

    /* 1️ Prevent duplicate email */
    $stmt = $db->prepare("SELECT pat_id FROM patient WHERE pat_email = ?");
    $stmt->bind_param("s", $data['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        throw new Exception("Email already registered");
    }
    $stmt->close();

    /*  Insert patient */
    $stmt = $db->prepare("
        INSERT INTO patient 
        (pat_name, pat_lname, pat_birthday, pat_gender, telephone, pat_email, pat_password)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssssss",
        $data['first_name'],
        $data['last_name'],
        $data['birth_date'],
        $data['gender'],
        $data['phone'],
        $data['email'],
        $data['password']
    );

    $stmt->execute();
    $patId = $db->insert_id;
    $stmt->close();

    /* 3️⃣ Prepare nullable medical fields */
    $allergies = $data['allergies'] ?? null;
    $chronic   = $data['chronic'] ?? null;

    /* 4️⃣ Insert medical info */
    $stmt = $db->prepare("
        INSERT INTO patient_medical
        (pat_id, allergies, chronic_diseases, emergency_name, emergency_phone)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "issss",
        $patId,
        $allergies,
        $chronic,
        $data['emergency_name'],
        $data['emergency_phone']
    );

    $stmt->execute();
    $stmt->close();

    /* .*/
    $db->commit();

} catch (Exception $e) {
    $db->rollback();
    die("Registration failed: " . $e->getMessage());
}

/* Auto-login */
$_SESSION['patient_id'] = $patId;
$_SESSION['role'] = 'patient';

/*  Cleanup */
unset($_SESSION['register']);

/*  Redirect */
header("Location: ../patient/dashboard.html");
exit;
