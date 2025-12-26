<?php
// setup_auto.php - Configuration automatique COMPLÈTE
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Setup Auto</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .box { padding: 20px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; }
        .error { background: #f8d7da; }
        .info { background: #d1ecf1; }
    </style>
</head>
<body>
<h1>⚙️ Configuration Automatique</h1>";

// Étape 1: Créer la structure de dossiers
echo "<div class='info box'><h3>1. Création des dossiers</h3>";
$folders = ['config', 'models', 'controllers', 'api'];
foreach($folders as $folder) {
    if(!is_dir($folder)) {
        mkdir($folder, 0777, true);
        echo "✅ Créé: $folder/<br>";
    } else {
        echo "✅ Existe déjà: $folder/<br>";
    }
}
echo "</div>";

// Étape 2: Créer config/Database.php
echo "<div class='info box'><h3>2. Configuration de la base de données</h3>";
$db_config = '<?php
namespace Config;

class Database {
    private static $instance = null;
    private $connection;
    
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "patient_dashboard";
    
    private function __construct() {
        try {
            $this->connection = new \PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (\PDOException $e) {
            // Si la base n\'existe pas, on la crée
            $this->createDatabaseAndTables();
        }
    }
    
    private function createDatabaseAndTables() {
        // Connexion sans base de données
        $temp = new \PDO("mysql:host={$this->host}", $this->username, $this->password);
        
        // Créer la base
        $temp->exec("CREATE DATABASE IF NOT EXISTS {$this->dbname}");
        $temp->exec("USE {$this->dbname}");
        
        // Tables
        $sql = file_get_contents(__DIR__ . \'/../app.sql\');
        $temp->exec($sql);
        
        // Reconnecter avec la base
        $this->connection = new \PDO(
            "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
            $this->username,
            $this->password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}
?>';

if(file_put_contents('config/Database.php', $db_config)) {
    echo "✅ config/Database.php créé<br>";
} else {
    echo "❌ Erreur création config/Database.php<br>";
}
echo "</div>";

// Étape 3: Créer app.sql
echo "<div class='info box'><h3>3. Script SQL de création</h3>";
$sql_content = "-- Création de la base de données
CREATE DATABASE IF NOT EXISTS patient_dashboard;
USE patient_dashboard;

-- Patients
CREATE TABLE IF NOT EXISTS patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    date_of_birth DATE,
    blood_type VARCHAR(5),
    insurance_id VARCHAR(50),
    emergency_contact VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Docteurs
CREATE TABLE IF NOT EXISTS doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL
);

-- Rendez-vous
CREATE TABLE IF NOT EXISTS appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

-- Données de test
INSERT IGNORE INTO patients (username, password, full_name, email, phone, date_of_birth, blood_type, insurance_id, emergency_contact, address) VALUES
('sarahj', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah Johnson', 'sarah@test.com', '+1234567890', '1985-06-15', 'A+', 'INS123', 'John +0987654321', '123 Main St');

INSERT IGNORE INTO doctors (name, specialty, email) VALUES
('Dr. Smith', 'Cardiologue', 'smith@hospital.com'),
('Dr. Chen', 'Dermatologue', 'chen@hospital.com');

INSERT IGNORE INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason) VALUES
(1, 1, '2024-12-20', '10:00:00', 'Contrôle régulier');";

if(file_put_contents('app.sql', $sql_content)) {
    echo "✅ app.sql créé<br>";
} else {
    echo "❌ Erreur création app.sql<br>";
}
echo "</div>";

// Étape 4: Créer login.php SIMPLE
echo "<div class='info box'><h3>4. Page de connexion</h3>";
$login_content = '<?php
session_start();
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Test avec utilisateur simple
    if($username == "sarahj" && $password == "password123") {
        $_SESSION["patient_id"] = 1;
        $_SESSION["patient_name"] = "Sarah Johnson";
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: Arial; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-box { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 0 20px rgba(0,0,0,0.2); 
            width: 300px; 
        }
        .login-box h2 { color: #667eea; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #667eea; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🏥 Connexion Patient</h2>
        <?php if($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Nom d\'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p style="text-align: center; margin-top: 20px; font-size: 12px;">
            Utilisateur: <strong>sarahj</strong><br>
            Mot de passe: <strong>password123</strong>
        </p>
    </div>
</body>
</html>';

if(file_put_contents('login.php', $login_content)) {
    echo "✅ login.php créé<br>";
} else {
    echo "❌ Erreur création login.php<br>";
}
echo "</div>";

// Étape 5: Créer dashboard.php SIMPLE
echo "<div class='info box'><h3>5. Tableau de bord</h3>";
$dashboard_content = '<?php
session_start();
if(!isset($_SESSION["patient_id"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Patient</title>
    <style>
        body { font-family: Arial; margin: 0; }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .nav { background: white; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .nav button { 
            padding: 10px 20px; 
            margin: 0 5px; 
            border: none; 
            background: #667eea; 
            color: white; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        .content { padding: 20px; }
        .card { 
            background: white; 
            padding: 20px; 
            margin: 10px 0; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Tableau de bord Patient</h1>
        <div>
            <span>Bienvenue, <?php echo $_SESSION["patient_name"]; ?></span>
            <button onclick="logout()" style="margin-left: 20px;">Déconnexion</button>
        </div>
    </div>
    
    <div class="nav">
        <button onclick="showSection(\'appointments\')">📅 Rendez-vous</button>
        <button onclick="showSection(\'booking\')">➕ Nouveau</button>
        <button onclick="showSection(\'profile\')">👤 Profil</button>
    </div>
    
    <div class="content" id="content">
        <div class="card">
            <h2>Bienvenue sur votre tableau de bord</h2>
            <p>Sélectionnez une option dans le menu.</p>
        </div>
    </div>
    
    <script>
        function showSection(section) {
            let html = "";
            switch(section) {
                case "appointments":
                    html = `<div class="card">
                        <h2>📅 Mes rendez-vous</h2>
                        <p><strong>20 Décembre 2024 - 10:00</strong><br>
                        Dr. Smith - Cardiologue<br>
                        Contrôle régulier</p>
                    </div>`;
                    break;
                case "booking":
                    html = `<div class="card">
                        <h2>➕ Nouveau rendez-vous</h2>
                        <input type="date" style="padding: 10px; width: 200px; margin: 10px 0;"><br>
                        <input type="time" style="padding: 10px; width: 200px; margin: 10px 0;"><br>
                        <button style="padding: 10px 20px;">Réserver</button>
                    </div>`;
                    break;
                case "profile":
                    html = `<div class="card">
                        <h2>👤 Mon profil</h2>
                        <p><strong>Nom:</strong> Sarah Johnson</p>
                        <p><strong>Email:</strong> sarah@test.com</p>
                        <p><strong>Téléphone:</strong> +1234567890</p>
                    </div>`;
                    break;
            }
            document.getElementById("content").innerHTML = html;
        }
        
        function logout() {
            if(confirm("Se déconnecter ?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>';

if(file_put_contents('dashboard.php', $dashboard_content)) {
    echo "✅ dashboard.php créé<br>";
} else {
    echo "❌ Erreur création dashboard.php<br>";
}
echo "</div>";

// Étape 6: Créer logout.php
echo "<div class='info box'><h3>6. Déconnexion</h3>";
$logout_content = '<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>';

if(file_put_contents('logout.php', $logout_content)) {
    echo "✅ logout.php créé<br>";
} else {
    echo "❌ Erreur création logout.php<br>";
}
echo "</div>";

// Étape 7: Créer index.php
echo "<div class='info box'><h3>7. Page d\'accueil</h3>";
$index_content = '<?php
session_start();
if(isset($_SESSION["patient_id"])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit();
?>';

if(file_put_contents('index.php', $index_content)) {
    echo "✅ index.php créé<br>";
} else {
    echo "❌ Erreur création index.php<br>";
}
echo "</div>";

echo "<div class='success box'><h3>✅ Configuration terminée !</h3>";
echo "<p><strong>Accès immédiat:</strong></p>";
echo "<p>1. <a href='login.php' style='color: #155724; font-weight: bold;'>👉 Cliquez ici pour aller à la page de connexion</a></p>";
echo "<p>2. Utilisez ces identifiants:</p>";
echo "<ul>
    <li><strong>Utilisateur:</strong> sarahj</li>
    <li><strong>Mot de passe:</strong> password123</li>
</ul>";
echo "</div>";

echo "</body></html>";
?>