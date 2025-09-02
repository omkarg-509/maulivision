<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function countVendor()
    {
        $sql = "SELECT COUNT(*) as total FROM vendor";
        $stmt = $this->db2->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
