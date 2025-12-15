<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'medicalclinic';
    
    private function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->name);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8mb4");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    public function escape($data) {
        return $this->connection->real_escape_string($data);
    }
    
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
}
?>
