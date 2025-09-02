<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getSuperAdminCount(): int
    {
        $sql = "SELECT COUNT(*) AS cnt FROM superadmin"; // adjust table name if different
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['cnt']) ? (int)$row['cnt'] : 0;
    }

 
}
