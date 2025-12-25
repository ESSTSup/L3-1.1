<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_config.php';
require_once 'validator.php'; // Added validator

// Get PDO connection
try {
    $pdo = getPDOConnection();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage(),
        'data' => null
    ]);
    exit;
}

function respond($success, $message = '', $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

/* =========================
   READ CLINICS
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['action'])) {
    try {
        // Debug: Check if table exists
        $checkTable = $pdo->query("SHOW TABLES LIKE 'clinics'");
        if ($checkTable->rowCount() == 0) {
            respond(false, 'Table "clinics" does not exist');
        }
        
        // Check if doctors table exists
        $checkDoctorsTable = $pdo->query("SHOW TABLES LIKE 'doctor'");
        if ($checkDoctorsTable->rowCount() == 0) {
            respond(false, 'Table "doctor" does not exist');
        }
        
        $stmt = $pdo->query("
            SELECT 
                c.clinic_id,
                c.clinic_name,
                c.clinic_email,
                c.clinic_phone,
                c.city,
                c.state,
                c.subscription_plan,
                c.archived,
                (
                    SELECT COUNT(*) 
                    FROM doctor d 
                    WHERE d.clinic_id = c.clinic_id
                ) AS doctor_count
            FROM clinics c
            WHERE c.archived = 0
            ORDER BY c.clinic_id DESC
        ");

        $clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        respond(true, 'Clinics loaded', $clinics);
    } catch (PDOException $e) {
        respond(false, 'Error loading clinics: ' . $e->getMessage());
    } catch (Exception $e) {
        respond(false, 'General error: ' . $e->getMessage());
    }
}

/* =========================
   CREATE CLINIC + PRINCIPAL DOCTOR
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) respond(false, 'Invalid JSON');

        // Use the Validator
        $validator = new Validator($data);
        
        if (!$validator->validateClinicCreation($data)) {
            respond(false, $validator->getErrorString());
        }

        // Check clinic email uniqueness
        $check = $pdo->prepare("SELECT clinic_id FROM clinics WHERE clinic_email = ?");
        $check->execute([$data['clinic_email']]);
        if ($check->fetch()) {
            respond(false, 'Clinic email already exists');
        }
        
        // Check doctor email uniqueness
        $checkDoctor = $pdo->prepare("SELECT doc_id FROM doctor WHERE doc_email = ?");
        $checkDoctor->execute([$data['principal_doctor_email']]);
        if ($checkDoctor->fetch()) {
            respond(false, 'Doctor email already exists');
        }

        // insert clinic
        $stmt = $pdo->prepare("
            INSERT INTO clinics (
                clinic_email, clinic_password, clinic_name, clinic_phone,
                address, city, state, postal_code,
                subscription_plan, handicap_accessible, number_of_doctors, archived
            ) VALUES (?,?,?,?,?,?,?,?,?,?,1,0)
        ");

        $stmt->execute([
            $data['clinic_email'],
            password_hash($data['clinic_password'], PASSWORD_DEFAULT),
            $data['clinic_name'],
            $data['clinic_phone'],
            $data['address'],
            $data['city'],
            $data['state'],
            $data['postal_code'],
            $data['subscription_plan'] ?? 'free',
            $data['handicap_accessible'] ?? 'not-accessible'
        ]);

        $clinicId = $pdo->lastInsertId();

        // insert principal doctor
        $stmt = $pdo->prepare("
            INSERT INTO doctor (
                doc_email, doc_password, doc_specialite,
                doc_telephone, doc_role, doc_name, doc_lname,
                clinic_id, is_principal
            ) VALUES (?,?,?,?,?,?,?,?,1)
        ");

        $stmt->execute([
            $data['principal_doctor_email'],
            password_hash($data['principal_doctor_password'], PASSWORD_DEFAULT),
            $data['principal_doctor_specialite'] ?? 'General Medicine',
            $data['clinic_phone'],
            'admin',
            $data['principal_doctor_name'],
            $data['principal_doctor_lname'],
            $clinicId
        ]);

        respond(true, 'Clinic created successfully', [
            'credentials' => [
                'clinic' => [
                    'email' => $data['clinic_email'],
                    'password' => $data['clinic_password']
                ],
                'doctor' => [
                    'email' => $data['principal_doctor_email'],
                    'password' => $data['principal_doctor_password']
                ]
            ]
        ]);

    } catch (PDOException $e) {
        respond(false, 'Error creating clinic: ' . $e->getMessage());
    }
}

/* =========================
   ARCHIVE CLINIC
========================= */
if (isset($_GET['action']) && $_GET['action'] === 'archive') {
    if (empty($_GET['id'])) respond(false, 'Missing clinic id');

    try {
        $stmt = $pdo->prepare("
            UPDATE clinics 
            SET archived = 1, archived_at = NOW()
            WHERE clinic_id = ?
        ");
        $stmt->execute([$_GET['id']]);

        respond(true, 'Clinic archived');
    } catch (PDOException $e) {
        respond(false, 'Error archiving clinic: ' . $e->getMessage());
    }
}

/* =========================
   GET SINGLE CLINIC DETAILS
========================= */
if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])) {
    try {
        // Get clinic details
        $stmt = $pdo->prepare("
            SELECT 
                c.*,
                (
                    SELECT COUNT(*) 
                    FROM doctor d 
                    WHERE d.clinic_id = c.clinic_id
                ) AS doctor_count
            FROM clinics c
            WHERE c.clinic_id = ?
        ");
        $stmt->execute([$_GET['id']]);
        $clinic = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$clinic) {
            respond(false, 'Clinic not found');
        }
        
        // Get doctors for this clinic
        $stmt = $pdo->prepare("
            SELECT 
                doc_id, doc_name, doc_lname, doc_email, 
                doc_specialite, doc_role, is_principal
            FROM doctor 
            WHERE clinic_id = ?
            ORDER BY is_principal DESC, doc_name ASC
        ");
        $stmt->execute([$_GET['id']]);
        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        respond(true, 'Clinic details loaded', [
            'clinic' => $clinic,
            'doctors' => $doctors
        ]);
    } catch (PDOException $e) {
        respond(false, 'Error loading clinic details: ' . $e->getMessage());
    }
}

/* =========================
   DASHBOARD STATS
========================= */
if (isset($_GET['action']) && $_GET['action'] === 'stats') {
    try {
        // Total active clinics
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM clinics WHERE archived = 0");
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Premium clinics
        $stmt = $pdo->query("SELECT COUNT(*) as premium FROM clinics WHERE subscription_plan = 'premium' AND archived = 0");
        $premium = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Archived clinics (last 30 days)
        $stmt = $pdo->query("SELECT COUNT(*) as archived FROM clinics WHERE archived = 1 AND archived_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $archived = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Total doctors
        $stmt = $pdo->query("SELECT COUNT(*) as doctors FROM doctor d JOIN clinics c ON d.clinic_id = c.clinic_id WHERE c.archived = 0");
        $doctors = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Recent clinics (last 30 days)
        $stmt = $pdo->query("
            SELECT clinic_name, clinic_email, city, state, subscription_plan, created_at 
            FROM clinics 
            WHERE archived = 0 AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Monthly revenue (premium clinics * $49.99)
        $monthlyRevenue = $premium['premium'] * 49.99;
        
        respond(true, 'Dashboard stats loaded', [
            'total_clinics' => (int)$total['total'],
            'premium_clinics' => (int)$premium['premium'],
            'archived_clinics' => (int)$archived['archived'],
            'active_doctors' => (int)$doctors['doctors'],
            'monthly_revenue' => $monthlyRevenue,
            'recent_clinics' => $recent
        ]);
    } catch (PDOException $e) {
        respond(false, 'Error loading dashboard stats: ' . $e->getMessage());
    }
}

/* =========================
   SUBSCRIPTION REQUESTS
========================= */

/* GET ALL REQUESTS */
if (isset($_GET['action']) && $_GET['action'] === 'get_requests') {
    try {
        $stmt = $pdo->query("
            SELECT 
                sr.*,
                c.clinic_email,
                c.city,
                c.state
            FROM subscription_requests sr
            JOIN clinics c ON sr.clinic_id = c.clinic_id
            WHERE sr.status = 'pending'
            ORDER BY sr.request_date DESC
        ");
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        respond(true, 'Subscription requests loaded', $requests);
    } catch (PDOException $e) {
        respond(false, 'Error loading requests: ' . $e->getMessage());
    }
}

/* CREATE SUBSCRIPTION REQUEST */
if (isset($_GET['action']) && $_GET['action'] === 'create_request') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        // Use validator
        $validator = new Validator($data);
        if (!$validator->validateSubscriptionRequest($data)) {
            respond(false, $validator->getErrorString());
        }
        
        // Get clinic current subscription
        $stmt = $pdo->prepare("
            SELECT clinic_id, clinic_name, subscription_plan 
            FROM clinics 
            WHERE clinic_id = ?
        ");
        $stmt->execute([$data['clinic_id']]);
        $clinic = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$clinic) {
            respond(false, 'Clinic not found');
        }
        
        // Check if there's already a pending request
        $checkStmt = $pdo->prepare("
            SELECT request_id 
            FROM subscription_requests 
            WHERE clinic_id = ? AND status = 'pending'
        ");
        $checkStmt->execute([$data['clinic_id']]);
        
        if ($checkStmt->fetch()) {
            respond(false, 'You already have a pending request');
        }
        
        // Insert new request
        $stmt = $pdo->prepare("
            INSERT INTO subscription_requests 
            (clinic_id, clinic_name, current_plan, requested_plan, request_date, status)
            VALUES (?, ?, ?, ?, NOW(), 'pending')
        ");
        
        $stmt->execute([
            $data['clinic_id'],
            $clinic['clinic_name'],
            $clinic['subscription_plan'],
            $data['requested_plan']
        ]);
        
        respond(true, 'Subscription request submitted successfully');
    } catch (PDOException $e) {
        respond(false, 'Error creating request: ' . $e->getMessage());
    }
}

/* PROCESS REQUEST (APPROVE/REJECT) */
if (isset($_GET['action']) && $_GET['action'] === 'process_request') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Get request details
        $stmt = $pdo->prepare("
            SELECT * FROM subscription_requests 
            WHERE request_id = ? AND status = 'pending'
        ");
        $stmt->execute([$data['request_id']]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            $pdo->rollBack();
            respond(false, 'Request not found or already processed');
        }
        
        // Update request status
        $stmt = $pdo->prepare("
            UPDATE subscription_requests 
            SET status = ?, 
                processed_date = NOW(),
                notes = ?
            WHERE request_id = ?
        ");
        $stmt->execute([
            $data['action'], // 'approved' or 'rejected'
            $data['notes'] ?? null,
            $data['request_id']
        ]);
        
        // If approved, update clinic subscription
        if ($data['action'] === 'approved') {
            $stmt = $pdo->prepare("
                UPDATE clinics 
                SET subscription_plan = ?, 
                    subscription_updated_at = NOW()
                WHERE clinic_id = ?
            ");
            $stmt->execute([
                $request['requested_plan'],
                $request['clinic_id']
            ]);
        }
        
        $pdo->commit();
        respond(true, "Request {$data['action']} successfully");
    } catch (PDOException $e) {
        $pdo->rollBack();
        respond(false, 'Error processing request: ' . $e->getMessage());
    }
}

/* UPDATE CLINIC SUBSCRIPTION DIRECTLY */
if (isset($_GET['action']) && $_GET['action'] === 'update_subscription') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        // Validate
        if (empty($data['clinic_id']) || empty($data['new_plan'])) {
            respond(false, 'Missing clinic_id or new_plan');
        }
        
        $validator = new Validator(['subscription_plan' => $data['new_plan']]);
        if (!$validator->validateSubscriptionPlan('subscription_plan')) {
            respond(false, $validator->getErrorString());
        }
        
        $stmt = $pdo->prepare("
            UPDATE clinics 
            SET subscription_plan = ?, 
                subscription_updated_at = NOW()
            WHERE clinic_id = ?
        ");
        $stmt->execute([
            $data['new_plan'],
            $data['clinic_id']
        ]);
        
        respond(true, 'Subscription updated successfully');
    } catch (PDOException $e) {
        respond(false, 'Error updating subscription: ' . $e->getMessage());
    }
}

/* UPDATE CLINIC INFORMATION */
if (isset($_GET['action']) && $_GET['action'] === 'update_clinic') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        // Use validator
        $validator = new Validator($data);
        if (!$validator->validateClinicUpdate($data)) {
            respond(false, $validator->getErrorString());
        }
        
        if (empty($data['clinic_id'])) {
            respond(false, 'Missing clinic_id');
        }
        
        $fields = [];
        $values = [];
        
        // Build dynamic update query
        $allowedFields = [
            'clinic_name', 'clinic_phone', 'address', 'city', 
            'state', 'postal_code', 'handicap_accessible', 
            'subscription_plan', 'number_of_doctors'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            respond(false, 'No fields to update');
        }
        
        // Add clinic_id to values
        $values[] = $data['clinic_id'];
        
        $sql = "UPDATE clinics SET " . implode(', ', $fields) . " WHERE clinic_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        
        respond(true, 'Clinic updated successfully');
    } catch (PDOException $e) {
        respond(false, 'Error updating clinic: ' . $e->getMessage());
    }
}




/* =========================
   RESTORE CLINIC
========================= */
if (isset($_GET['action']) && $_GET['action'] === 'restore') {
    if (empty($_GET['id'])) respond(false, 'Missing clinic id');

    try {
        $stmt = $pdo->prepare("
            UPDATE clinics 
            SET archived = 0
            WHERE clinic_id = ?
        ");
        $stmt->execute([$_GET['id']]);

        respond(true, 'Clinic restored');
    } catch (PDOException $e) {
        respond(false, 'Error restoring clinic: ' . $e->getMessage());
    }
}

/* =========================
   GET ARCHIVED CLINICS
========================= */
if (isset($_GET['action']) && $_GET['action'] === 'archived') {
    try {
        $stmt = $pdo->query("
            SELECT 
                c.clinic_id,
                c.clinic_name,
                c.clinic_email,
                c.clinic_phone,
                c.city,
                c.state,
                c.subscription_plan,
                c.archived_at,
                c.created_at,
                (SELECT COUNT(*) FROM doctor d WHERE d.clinic_id = c.clinic_id) AS doctor_count,
                DATEDIFF(DATE_ADD(c.archived_at, INTERVAL 30 DAY), NOW()) as days_remaining
            FROM clinics c
            WHERE c.archived = 1
            ORDER BY c.archived_at DESC
        ");

        $clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        respond(true, 'Archived clinics loaded', $clinics);
    } catch (PDOException $e) {
        respond(false, 'Error loading archived clinics: ' . $e->getMessage());
    }
}
respond(false, 'Invalid request');
?>