<?php
require_once '../core/Database.php';

class Staff extends Database{
    
    public function insert($data)
    {
        $stmt = $this->db->prepare("INSERT INTO staff (vid,name,number,address,status) VALUES (?,?,?,?,?)");
        $stmt->bind_param("isssi", $data['vid'], $data['name'], $data['number'], $data['address'], $data['status']);
        $success = $stmt->execute();
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


