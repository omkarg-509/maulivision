<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function countSuperAdmins()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM superadmin");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }
}
