<?php
require_once '../core/Database.php';

class Staff extends Database{
    
    public function insert($data)
    {
        // Ensure all required keys exist in $data
        $required = ['vid', 'name', 'number', 'address', 'status'];
        foreach ($required as $key) {
            if (!isset($data[$key])) {
                throw new Exception("Missing required field: " . $key);
            }
        }

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


