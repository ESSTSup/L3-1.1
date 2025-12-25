<?php
// superadmin/models/ClinicModel.php
require_once '../models/Validator.php';

class ClinicModel {
    private $conn;
    private $validator;
    
    public function __construct() {
        // Get connection from db_config.php in parent folder
        require_once '../../database/db_config.php';
        global $conn;
        $this->conn = $conn;
        
        $this->validator = new Validator();
    }
    
    public function createClinic($data) {
        // Validate
        $errors = $this->validator->validateClinicData($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Check email exists
        if ($this->emailExists($data['clinic_email'])) {
            return ['success' => false, 'errors' => ['clinic_email' => 'Email already exists']];
        }
        
        // Generate clinic code
        $clinicCode = $this->generateClinicCode($data['clinic_name']);
        
        // Hash password
        $plainPassword = empty($data['password']) ? $this->generatePassword() : $data['password'];
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        
        // Prepare SQL
        $sql = "INSERT INTO clinics (
            clinic_name, clinic_email, clinic_phone, address, city, state,
            postal_code, country, subscription_plan, clinic_code, password,
            principal_doctor_email, handicap_accessible, number_of_doctors,
            gps_latitude, gps_longitude, archived
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        
        $params = [
            $data['clinic_name'],
            $data['clinic_email'],
            $data['clinic_phone'],
            $data['address'],
            $data['city'],
            $data['state'],
            $data['postal_code'],
            $data['country'] ?? 'Algeria',
            $data['subscription_plan'] ?? 'free',
            $clinicCode,
            $hashedPassword,
            $data['principal_doctor_email'] ?? null,
            $data['handicap_accessible'] ?? 'not-accessible',
            $data['number_of_doctors'] ?? 1,
            $data['gps_latitude'] ?? null,
            $data['gps_longitude'] ?? null,
            0 // Not archived
        ];
        
        if ($stmt) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            
            if ($stmt->execute()) {
                $clinicId = $stmt->insert_id;
                return [
                    'success' => true,
                    'clinic_id' => $clinicId,
                    'clinic_code' => $clinicCode,
                    'password' => $plainPassword
                ];
            }
        }
        
        return ['success' => false, 'errors' => ['database' => 'Failed to create clinic']];
    }
    
    public function getAllClinics($archived = false) {
        $sql = "SELECT * FROM clinics WHERE archived = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $archivedInt = $archived ? 1 : 0;
        $stmt->bind_param('i', $archivedInt);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $clinics = [];
        
        while ($row = $result->fetch_assoc()) {
            $clinics[] = $row;
        }
        
        return $clinics;
    }
    
    public function getClinicById($id) {
        $sql = "SELECT * FROM clinics WHERE clinic_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function updateClinic($id, $data) {
        $clinic = $this->getClinicById($id);
        if (!$clinic) {
            return ['success' => false, 'errors' => ['general' => 'Clinic not found']];
        }
        
        $errors = $this->validator->validateClinicData($data, true);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        if (isset($data['clinic_email']) && $data['clinic_email'] !== $clinic['clinic_email']) {
            if ($this->emailExists($data['clinic_email'], $id)) {
                return ['success' => false, 'errors' => ['clinic_email' => 'Email already exists']];
            }
        }
        
        $updates = [];
        $params = [];
        $types = '';
        
        $fields = [
            'clinic_name', 'clinic_email', 'clinic_phone', 'address',
            'city', 'state', 'postal_code', 'country', 'subscription_plan',
            'handicap_accessible', 'number_of_doctors', 'gps_latitude', 'gps_longitude'
        ];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $data[$field];
                $types .= 's';
            }
        }
        
        if (empty($updates)) {
            return ['success' => false, 'errors' => ['general' => 'No data to update']];
        }
        
        $params[] = $id;
        $types .= 'i';
        
        $sql = "UPDATE clinics SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE clinic_id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            
            if ($stmt->execute()) {
                return ['success' => true, 'affected_rows' => $stmt->affected_rows];
            }
        }
        
        return ['success' => false, 'errors' => ['database' => 'Update failed']];
    }
    
    public function archiveClinic($id) {
        $sql = "UPDATE clinics SET archived = 1, updated_at = NOW() WHERE clinic_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'affected_rows' => $stmt->affected_rows];
        }
        
        return ['success' => false, 'errors' => ['database' => 'Archive failed']];
    }
    
    public function restoreClinic($id) {
        $sql = "UPDATE clinics SET archived = 0, updated_at = NOW() WHERE clinic_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'affected_rows' => $stmt->affected_rows];
        }
        
        return ['success' => false, 'errors' => ['database' => 'Restore failed']];
    }
    
    public function deleteClinic($id) {
        $sql = "DELETE FROM clinics WHERE clinic_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'affected_rows' => $stmt->affected_rows];
        }
        
        return ['success' => false, 'errors' => ['database' => 'Delete failed']];
    }
    
    public function searchClinics($term, $archived = false) {
        $sql = "SELECT * FROM clinics WHERE archived = ? AND 
                (clinic_name LIKE ? OR clinic_email LIKE ? OR clinic_code LIKE ? OR 
                 city LIKE ? OR state LIKE ?)
                ORDER BY clinic_name";
        
        $stmt = $this->conn->prepare($sql);
        $archivedInt = $archived ? 1 : 0;
        $searchTerm = "%$term%";
        
        $stmt->bind_param('isssss', $archivedInt, $searchTerm, $searchTerm, $searchTerm, 
                         $searchTerm, $searchTerm);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $clinics = [];
        
        while ($row = $result->fetch_assoc()) {
            $clinics[] = $row;
        }
        
        return $clinics;
    }
    
    private function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT clinic_id FROM clinics WHERE clinic_email = ? AND clinic_id != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('si', $email, $excludeId);
        } else {
            $sql = "SELECT clinic_id FROM clinics WHERE clinic_email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $email);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    private function generateClinicCode($name) {
        $prefix = strtoupper(preg_replace('/[^A-Z]/i', '', $name));
        $prefix = substr($prefix, 0, min(5, strlen($prefix)));
        
        for ($i = 0; $i < 10; $i++) {
            $code = $prefix . '-' . rand(100, 999);
            
            $sql = "SELECT clinic_id FROM clinics WHERE clinic_code = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $code);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows === 0) {
                return $code;
            }
        }
        
        return 'CL-' . time() . '-' . rand(100, 999);
    }
    
    private function generatePassword($length = 12) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $password;
    }
}
?>