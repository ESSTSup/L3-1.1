<?php
class Validator {
    private $errors = [];
    private $data = [];
    
    public function __construct($data = []) {
        $this->data = $this->sanitize($data);
    }
    
    // Validate email format
    public function validateEmail($field, $label = 'Email') {
        $email = $this->get($field);
        
        if (empty($email)) {
            $this->addError($field, "$label is required");
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "Invalid $label format");
            return false;
        }
        
        // Check for proper domain format
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($pattern, $email)) {
            $this->addError($field, "$label must contain @ and a valid domain name");
            return false;
        }
        
        return true;
    }
    
    // Validate clinic name
    public function validateClinicName($field = 'clinic_name', $label = 'Clinic name') {
        $name = $this->get($field);
        
        if (empty($name)) {
            $this->addError($field, "$label is required");
            return false;
        }
        
        if (strlen($name) < 3) {
            $this->addError($field, "$label must be at least 3 characters");
            return false;
        }
        
        if (strlen($name) > 100) {
            $this->addError($field, "$label must be less than 100 characters");
            return false;
        }
        
        if (!preg_match('/^[a-zA-Z0-9\s\-\.\',&()]+$/', $name)) {
            $this->addError($field, "$label contains invalid characters");
            return false;
        }
        
        return true;
    }
    
    // Validate phone number
    public function validatePhone($field = 'clinic_phone', $label = 'Phone') {
        $phone = $this->get($field);
        
        if (empty($phone)) {
            $this->addError($field, "$label is required");
            return false;
        }
        
        // Remove all non-numeric characters
        $digitsOnly = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($digitsOnly) < 10) {
            $this->addError($field, "$label must be at least 10 digits");
            return false;
        }
        
        // Moroccan phone format: 05, 06, or 07 followed by 8 digits
        if (!preg_match('/^(05|06|07)[0-9]{8}$/', $digitsOnly)) {
            $this->addError($field, "$label must be in format 05xxxxxxxx, 06xxxxxxxx, or 07xxxxxxxx");
            return false;
        }
        
        return true;
    }
    
    // Validate password
    public function validatePassword($field, $label = 'Password') {
        $password = $this->get($field);
        
        if (empty($password)) {
            $this->addError($field, "$label is required");
            return false;
        }
        
        if (strlen($password) < 6) {
            $this->addError($field, "$label must be at least 6 characters");
            return false;
        }
        
        if (strlen($password) > 50) {
            $this->addError($field, "$label must be less than 50 characters");
            return false;
        }
        
        return true;
    }
    
    // Validate required field
    public function required($field, $label = null) {
        $value = $this->get($field);
        
        if ($label === null) {
            $label = ucfirst(str_replace('_', ' ', $field));
        }
        
        if (empty(trim($value))) {
            $this->addError($field, "$label is required");
            return false;
        }
        
        return true;
    }
    
    // Validate string length
    public function length($field, $min = 1, $max = 255, $label = null) {
        $value = $this->get($field);
        
        if ($label === null) {
            $label = ucfirst(str_replace('_', ' ', $field));
        }
        
        $len = strlen($value);
        
        if ($len < $min) {
            $this->addError($field, "$label must be at least $min characters");
            return false;
        }
        
        if ($len > $max) {
            $this->addError($field, "$label must be less than $max characters");
            return false;
        }
        
        return true;
    }
    
    // Validate subscription plan
    public function validateSubscriptionPlan($field = 'subscription_plan') {
        $plan = $this->get($field);
        
        $validPlans = ['free', 'premium'];
        if (!empty($plan) && !in_array($plan, $validPlans)) {
            $this->addError($field, "Invalid subscription plan");
            return false;
        }
        
        return true;
    }
    
    // Validate number
    public function validateNumber($field, $min = 1, $max = 999, $label = null) {
        $value = $this->get($field);
        
        if ($label === null) {
            $label = ucfirst(str_replace('_', ' ', $field));
        }
        
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, "$label must be a number");
            return false;
        }
        
        if (!empty($value)) {
            $value = (int)$value;
            
            if ($value < $min) {
                $this->addError($field, "$label must be at least $min");
                return false;
            }
            
            if ($value > $max) {
                $this->addError($field, "$label must be less than $max");
                return false;
            }
        }
        
        return true;
    }
    
    // Validate doctor specialization
    public function validateSpecialization($field = 'principal_doctor_specialite', $label = 'Specialization') {
        $specialization = $this->get($field);
        
        if (empty($specialization)) {
            $this->addError($field, "$label is required");
            return false;
        }
        
        if (strlen($specialization) < 2) {
            $this->addError($field, "$label must be at least 2 characters");
            return false;
        }
        
        if (strlen($specialization) > 100) {
            $this->addError($field, "$label must be less than 100 characters");
            return false;
        }
        
        return true;
    }
    
    // Validate handicap access
    public function validateHandicapAccess($field = 'handicap_accessible', $label = 'Handicap access') {
        $access = $this->get($field);
        
        $validAccess = ['handicap-friendly', 'not-accessible', 'partial'];
        if (!empty($access) && !in_array($access, $validAccess)) {
            $this->addError($field, "Invalid $label value");
            return false;
        }
        
        return true;
    }
    
    // Validate all clinic creation data
    public function validateClinicCreation($data) {
        $this->data = $this->sanitize($data);
        $this->errors = [];
        
        // Clinic Information
        $this->validateClinicName('clinic_name');
        $this->validateEmail('clinic_email', 'Clinic email');
        $this->validatePassword('clinic_password', 'Clinic password');
        $this->validatePhone('clinic_phone');
        
        // Address Information
        $this->required('address', 'Address');
        $this->required('city', 'City');
        $this->required('state', 'State');
        $this->required('postal_code', 'Postal code');
        
        // Doctor Information
        $this->required('principal_doctor_name', 'Doctor first name');
        $this->required('principal_doctor_lname', 'Doctor last name');
        $this->validateEmail('principal_doctor_email', 'Doctor email');
        $this->validatePassword('principal_doctor_password', 'Doctor password');
        $this->validateSpecialization('principal_doctor_specialite', 'Doctor specialization');
        
        // Optional fields with validation
        $this->validateSubscriptionPlan('subscription_plan');
        $this->validateHandicapAccess('handicap_accessible');
        $this->validateNumber('number_of_doctors', 1, 20, 'Number of doctors');
        
        return empty($this->errors);
    }
    
    // Validate clinic update data
    public function validateClinicUpdate($data) {
        $this->data = $this->sanitize($data);
        $this->errors = [];
        
        // Only validate fields that are present
        if (isset($data['clinic_name'])) {
            $this->validateClinicName('clinic_name');
        }
        
        if (isset($data['clinic_email'])) {
            $this->validateEmail('clinic_email', 'Clinic email');
        }
        
        if (isset($data['clinic_phone'])) {
            $this->validatePhone('clinic_phone');
        }
        
        if (isset($data['subscription_plan'])) {
            $this->validateSubscriptionPlan('subscription_plan');
        }
        
        if (isset($data['handicap_accessible'])) {
            $this->validateHandicapAccess('handicap_accessible');
        }
        
        if (isset($data['number_of_doctors'])) {
            $this->validateNumber('number_of_doctors', 1, 20, 'Number of doctors');
        }
        
        if (isset($data['address'])) {
            $this->required('address', 'Address');
        }
        
        if (isset($data['city'])) {
            $this->required('city', 'City');
        }
        
        if (isset($data['state'])) {
            $this->required('state', 'State');
        }
        
        if (isset($data['postal_code'])) {
            $this->required('postal_code', 'Postal code');
        }
        
        return empty($this->errors);
    }
    
    // Validate subscription request
    public function validateSubscriptionRequest($data) {
        $this->data = $this->sanitize($data);
        $this->errors = [];
        
        $this->required('clinic_id', 'Clinic ID');
        $this->validateSubscriptionPlan('requested_plan');
        
        return empty($this->errors);
    }
    
    // Helper method to add error
    private function addError($field, $message) {
        $this->errors[$field] = $message;
    }
    
    // Get sanitized data
    private function get($field) {
        return isset($this->data[$field]) ? $this->data[$field] : '';
    }
    
    // Get all errors
    public function getErrors() {
        return $this->errors;
    }
    
    // Get error messages as string
    public function getErrorString() {
        $messages = [];
        foreach ($this->errors as $field => $message) {
            $messages[] = $message;
        }
        return implode(', ', $messages);
    }
    
    // Get specific error
    public function getError($field) {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }
    
    // Check if has errors
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    // Get sanitized data
    public function getData() {
        return $this->data;
    }
    
    // Get specific field value
    public function getField($field, $default = '') {
        return isset($this->data[$field]) ? $this->data[$field] : $default;
    }
    
    // Sanitize input
    public function sanitize($input) {
        if (is_array($input)) {
            $sanitized = [];
            foreach ($input as $key => $value) {
                $sanitized[$key] = $this->sanitize($value);
            }
            return $sanitized;
        }
        
        // Remove HTML tags and trim
        $input = trim($input);
        $input = strip_tags($input);
        
        return $input;
    }
}
?>