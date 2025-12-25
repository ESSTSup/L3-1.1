<?php


class DatabaseConfig {
    private static $instance = null;
    private $pdo = null;

    
    private $host = 'localhost';
    private $dbname = 'medicalclinic';
    private $username = 'root';
    private $password = ''; 
    private $charset = 'utf8mb4';

    private function __construct() {
       
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_PERSISTENT         => false,
                ];

                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                
                // Test connection
                $this->pdo->query("SELECT 1");
                
            } catch (PDOException $e) {
                throw new Exception("Database Connection Failed: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function closeConnection() {
        $this->pdo = null;
    }

   
    public static function getPDOConnection() {
        return self::getInstance()->getConnection();
    }
}


function getPDOConnection() {
    return DatabaseConfig::getPDOConnection();
}

function getDatabase() {
    return DatabaseConfig::getInstance()->getConnection();
}


function testDatabaseConnection() {
    try {
        $pdo = getPDOConnection();
        echo "✅ Database connection successful!<br>";
        
      
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "✅ Tables in database: " . implode(", ", $tables) . "<br>";
        
        return true;
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
        return false;
    }
}


?>