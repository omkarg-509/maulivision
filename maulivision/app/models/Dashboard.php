<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getSuperAdminCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM superadmin");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }

 
}
