<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getSuperAdminCount()
    {
        $result = $this->db->query("SELECT COUNT(*) AS count FROM superadmin");
        if ($result && $row = $result->fetch_assoc()) {
            return $row['count'];
        }
        return 0;
    }

 
}
