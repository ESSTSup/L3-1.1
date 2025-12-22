<?php
// superadmin/models/Validator.php
class Validator {
    public function validateClinicData($data, $update = false) {
        $errors = [];
        
        if (!$update) {
            $required = [
                'clinic_name', 'clinic_email', 'clinic_phone', 'address',
                'city', 'state', 'postal_code', 'subscription_plan'
            ];
            
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                }
            }
        }
        
        if (isset($data['clinic_email']) && !empty($data['clinic_email'])) {
            if (!filter_var($data['clinic_email'], FILTER_VALIDATE_EMAIL)) {
                $errors['clinic_email'] = 'Invalid email format';
            }
        }
        
        if (isset($data['clinic_phone']) && !empty($data['clinic_phone'])) {
            if (!$this->validateAlgerianPhone($data['clinic_phone'])) {
                $errors['clinic_phone'] = 'Invalid Algerian phone (10 digits starting with 05/06/07)';
            }
        }
        
        if (isset($data['number_of_doctors']) && !empty($data['number_of_doctors'])) {
            if (!is_numeric($data['number_of_doctors']) || $data['number_of_doctors'] < 1) {
                $errors['number_of_doctors'] = 'Must be at least 1';
            }
        }
        
        return $errors;
    }
    
    private function validateAlgerianPhone($phone) {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($clean) !== 10) {
            return false;
        }
        
        $prefix = substr($clean, 0, 2);
        return in_array($prefix, ['05', '06', '07']);
    }
}
?>