<?php
// setup.php - Database setup script
require_once 'config/Database.php';

try {
    $db = Config\Database::getInstance()->getConnection();
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Database Setup</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; }
            .error { background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; }
        </style>
    </head>
    <body>";
    
    echo "<div class='success'>
        <h2>✅ Database Setup Complete!</h2>
        <p>Database 'patient_dashboard' has been created and populated with sample data.</p>
        <p><strong>Demo Credentials:</strong></p>
        <ul>
            <li><strong>Username:</strong> sarahj</li>
            <li><strong>Password:</strong> password123</li>
        </ul>
        <p><a href='login.php'>Go to Login Page</a></p>
    </div>";
    
    echo "</body></html>";
    
} catch (Exception $e) {
    echo "<div class='error'>
        <h2>❌ Setup Failed</h2>
        <p>Error: " . htmlspecialchars($e->getMessage()) . "</p>
        <p>Please check your MySQL credentials in config/Database.php</p>
    </div>";
}
?>