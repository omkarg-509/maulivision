<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getAllVendors()
    {
        $stmt = $this->db->prepare("SELECT * FROM superadmin");
        $stmt->execute();
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
}
