<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getAll($vid = null)
    {
        if ($vid !== null) {
            $stmt = $this->db->prepare("SELECT * FROM customers WHERE d_status = ? AND vid = ? ORDER BY id DESC");
            $d_status = 0; // Use integer instead of string
            $stmt->bind_param("ii", $d_status, $vid);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM customers WHERE d_status = ? ORDER BY id DESC");
            $d_status = 0; // Use integer instead of string
            $stmt->bind_param("i", $d_status);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $customers = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $customers;
    }
}
