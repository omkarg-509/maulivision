<?php
require_once '../core/Database.php';

class Staff extends Database{
    
    public function insert($data)
    {
        // // Check if there is already a staff with the same vid
        // $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM staff WHERE vid = ?");
        // $stmt->bind_param("s", $data['vid']);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $row = $result->fetch_assoc();
        // $stmt->close();

        // if ($row['cnt'] > 5) {
        //     // Limit reached, do not insert
        //     return false;
        // }

        // Insert new staff
        $stmt = $this->db->prepare("INSERT INTO staff (vid,name,number,address,status) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $data['vid'], $data['name'], $data['number'], $data['address'], $data['status']);
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


