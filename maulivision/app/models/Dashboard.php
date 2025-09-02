<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getSuperAdminCount()
    {
        $result = $this->db->query("SELECT COUNT(*) AS count FROM superadmin");
        $row = $result->fetch_assoc();
        return $row;
    }

   
}
