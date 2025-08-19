<?php
require_once __DIR__ . '/../core/Database.php';

class Staff extends Database{
    
    public function insert($data)
    {
        // Ensure the staff table exists
        $this->createStaffTableIfNotExists();

        $stmt = $this->db->prepare("INSERT INTO staff (name, number, address, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['name'], $data['number'], $data['address'], $data['status']);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    private function createStaffTableIfNotExists()
    {
        $sql = "CREATE TABLE IF NOT EXISTS staff (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            number VARCHAR(50) NOT NULL,
            address VARCHAR(255) NOT NULL,
            status VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->db->query($sql);
    }
}


