<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function countVendor()
    {
        $sql = "SELECT COUNT(*) as total FROM superadmin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
