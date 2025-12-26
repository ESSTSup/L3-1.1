<?php

class Database {
    private $connection;
    
    public function __construct($host, $user, $pass, $name) {
        $this->connection = new mysqli($host, $user, $pass, $name);
        
        if ($this->connection->connect_error) {
            throw new Exception("Connection failed: " . $this->connection->connect_error);
        }
    }
    
    public static function create() {
        return new self('localhost', 'root', '', 'medicalclinic');
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function close() {
        $this->connection->close();
    }
}
?> 

