<?php
// test.php - Diagnostic complet
echo "<!DOCTYPE html><html><head><title>Diagnostic</title><style>
    body { font-family: Arial; padding: 20px; }
    .success { background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #fff3cd; padding: 15px; margin: 10px 0; border-radius: 5px; }
</style></head><body>";
echo "<h1>🔧 Diagnostic Patient Dashboard</h1>";

// Activer toutes les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Test 1: PHP fonctionne-t-il ?
echo "<div class='success'><h3>✅ PHP fonctionne correctement</h3>";
echo "Version PHP: " . phpversion() . "</div>";

// Test 2: Dossier courant
echo "<div class='warning'><h3>📁 Dossier courant</h3>";
echo getcwd() . "<br>";
echo "Contenu du dossier: <pre>";
$files = scandir('.');
foreach($files as $file) {
    if($file != '.' && $file != '..') {
        echo $file . "\n";
    }
}
echo "</pre></div>";

// Test 3: Session
session_start();
echo "<div class='success'><h3>✅ Sessions activées</h3>";
echo "Session ID: " . session_id() . "</div>";

// Test 4: Extensions PHP
echo "<div class='warning'><h3>🔧 Extensions PHP</h3>";
$extensions = ['pdo', 'pdo_mysql', 'mysqli', 'session', 'json'];
foreach($extensions as $ext) {
    if(extension_loaded($ext)) {
        echo "✅ $ext<br>";
    } else {
        echo "❌ $ext (MANQUANTE)<br>";
    }
}
echo "</div>";

// Test 5: Base de données
echo "<div class='warning'><h3>🗄️ Test Base de données</h3>";
try {
    // Test direct MySQLi
    $mysqli = @new mysqli('localhost', 'root', '');
    if($mysqli->connect_error) {
        echo "❌ Connexion MySQL échouée: " . $mysqli->connect_error . "<br>";
    } else {
        echo "✅ Connexion MySQL réussie<br>";
        
        // Créer base de données si elle n'existe pas
        $mysqli->query("CREATE DATABASE IF NOT EXISTS patient_dashboard");
        $mysqli->select_db("patient_dashboard");
        echo "✅ Base de données 'patient_dashboard' prête<br>";
        
        $mysqli->close();
    }
} catch(Exception $e) {
    echo "❌ Erreur MySQL: " . $e->getMessage() . "<br>";
}
echo "</div>";

// Test 6: Fichiers existants
echo "<div class='warning'><h3>📄 Fichiers du projet</h3>";
$required_files = [
    'config/Database.php' => 'Configuration BDD',
    'login.php' => 'Page de connexion',
    'dashboard.php' => 'Tableau de bord',
    'index.php' => 'Page d\'accueil'
];

foreach($required_files as $file => $desc) {
    if(file_exists($file)) {
        echo "✅ $file ($desc)<br>";
    } else {
        echo "❌ $file ($desc) - MANQUANT<br>";
    }
}
echo "</div>";

echo "<div class='success'><h3>🚀 Actions rapides</h3>";
echo "<a href='setup_auto.php' style='background: #667eea; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>1. Configurer automatiquement</a>";
echo "<a href='login_simple.php' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>2. Tester connexion simple</a>";
echo "<a href='dashboard_minimal.php' style='background: #ffc107; color: black; padding: 10px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block;'>3. Voir dashboard minimal</a>";
echo "</div>";

echo "</body></html>";
?>