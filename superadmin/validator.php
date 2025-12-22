<?php
class Validator {
    private $errors = [];
    
    // Validate email format
    public function validateEmail($email) {
        if (empty($email)) {
            $this->addError('clinic_email', "Email is required");
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('clinic_email', "Invalid email format");
            return false;
        }
        
        // Check for proper domain format
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($pattern, $email)) {
            $this->addError('clinic_email', "Email must contain @ and a valid domain name");
            return false;
        }
        
        return true;
    }
    
    // Validate clinic name
    public function validateClinicName($name) {
        if (empty($name)) {
            $this->addError('clinic_name', "Clinic name is required");
            return false;
        }
        
        if (strlen($name) < 3) {
            $this->addError('clinic_name', "Clinic name must be at least 3 characters");
            return false;
        }
        
        if (!preg_match('/^[a-zA-Z0-9\s\-\.\']+$/', $name)) {
            $this->addError('clinic_name', "Clinic name contains invalid characters");
            return false;
        }
        
        return true;
    }
    
    // Validate phone number
    public function validatePhone($phone) {
        if (empty($phone)) {
            $this->addError('clinic_phone', "Phone is required");
            return false;
        }
        
        // Remove all non-numeric characters except +
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Check if phone has at least 10 digits
        $digitsOnly = preg_replace('/[^0-9]/', '', $cleanPhone);
        
        if (strlen($digitsOnly) < 10) {
            $this->addError('clinic_phone', "Phone number must be at least 10 digits");
            return false;
        }
        
        return true;
    }
    
    // Validate all clinic data
    public function validateClinicData($data) {
        $this->errors = []; // Reset errors
        
        // Validate required fields
        $this->validateRequired('clinic_name', $data['clinic_name'], 'Clinic name');
        $this->validateRequired('clinic_email', $data['clinic_email'], 'Email');
        $this->validateRequired('clinic_phone', $data['clinic_phone'], 'Phone');
        $this->validateRequired('address', $data['address'], 'Address');
        $this->validateRequired('city', $data['city'], 'City');
        $this->validateRequired('state', $data['state'], 'State');
        $this->validateRequired('postal_code', $data['postal_code'], 'Postal code');
        $this->validateRequired('country', $data['country'], 'Country');
        $this->validateRequired('subscription_plan', $data['subscription_plan'], 'Subscription plan');
        
        // Only validate format if field is not empty
        if (!empty($data['clinic_name'])) {
            $this->validateClinicName($data['clinic_name']);
        }
        
        if (!empty($data['clinic_email'])) {
            $this->validateEmail($data['clinic_email']);
        }
        
        if (!empty($data['clinic_phone'])) {
            $this->validatePhone($data['clinic_phone']);
        }
        
        return empty($this->errors);
    }
    
    // Helper method to add error
    private function addError($field, $message) {
        $this->errors[$field] = $message;
    }
    
    // Validate required field
    private function validateRequired($field, $value, $label) {
        if (empty(trim($value))) {
            $this->addError($field, "$label is required");
            return false;
        }
        return true;
    }
    
    // Get all errors
    public function getErrors() {
        return $this->errors;
    }
    
    // Sanitize input
    public function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = $this->sanitize($value);
            }
            return $input;
        }
        
        return htmlspecialchars(strip_tags(trim($input)));
    }
    
    // Check if has errors
    public function hasErrors() {
        return !empty($this->errors);
    }
}
?>