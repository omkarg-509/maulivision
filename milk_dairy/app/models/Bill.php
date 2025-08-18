<?php

require_once '../core/Database.php';

class Bill extends Database{

    public function getById($bill_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM bills WHERE id = ?");
        $stmt->bind_param("i", $bill_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}