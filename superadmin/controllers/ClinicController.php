<?php
// superadmin/controllers/ClinicController.php

class ClinicController {
    
    public function __construct() {
        // Nothing needed for now
    }
    
    public function handleApi() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch($method) {
            case 'GET':
                $this->handleGet();
                break;
                
            case 'POST':
                $this->handlePost();
                break;
                
            default:
                http_response_code(405);
                echo json_encode(['success' => false, 'error' => 'Method not allowed']);
        }
    }
    
    private function handleGet() {
        // Simple test response
        echo json_encode([
            'success' => true,
            'message' => 'API is working!',
            'data' => [
                ['id' => 1, 'name' => 'Test Clinic 1'],
                ['id' => 2, 'name' => 'Test Clinic 2']
            ]
        ]);
    }
    
    private function handlePost() {
        // Get input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            $data = $_POST;
        }
        
        // Simple response
        echo json_encode([
            'success' => true,
            'message' => 'Data received successfully!',
            'received_data' => $data,
            'generated_id' => rand(1000, 9999)
        ]);
    }
}
?>  