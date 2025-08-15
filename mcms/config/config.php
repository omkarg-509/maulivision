<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'u367009900_mcms';
    private $username = 'u367009900_mcms';
    private $password = '!NK6N*17p';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Usage example:
$connection = (new Database())->getConnection();


class Header {
    public function render() {
        echo "<header><h1>Welcome to MCMS</h1></header>";
    }
}

class Footer {
    public function render() {
        echo "<footer><p>&copy; " . date("Y") . " MCMS. All rights reserved.</p></footer>";
    }
}


 /* ... your page content here ... */ 
