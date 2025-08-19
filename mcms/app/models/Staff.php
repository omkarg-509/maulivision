<?php
require_once '../core/Database.php';

class Staff extends Database{
    
    public function insert($data)
    {
        // // Ensure the staff table exists
        // $this->createStaffTableIfNotExists();

        // if (!$this->db) {
        //     throw new Exception("Database connection not established.");
        // }

        $stmt = $this->db->prepare("INSERT INTO staff (vid, name, number, address, status) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param(
            "sssss",
            $data['vid'],
            $data['name'],
            $data['number'],
            $data['address'],
            $data['status']
        );
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        return $success;
    }

    // private function createStaffTableIfNotExists()
    // {
    //     $sql = "CREATE TABLE IF NOT EXISTS staff (
    //         id INT AUTO_INCREMENT PRIMARY KEY,
    //         vid VARCHAR(100) NOT NULL,
    //         name VARCHAR(255) NOT NULL,
    //         number VARCHAR(50) NOT NULL,
    //         address VARCHAR(255) NOT NULL,
    //         status VARCHAR(50) NOT NULL,
    //         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    //     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    //     $this->db->query($sql);
    // }
    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM staff");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

      public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM staff WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}


