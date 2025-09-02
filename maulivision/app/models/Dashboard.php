<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
 
      public function getAllVendors()
    {
        $stmt = $this->db->prepare("SELECT * FROM superadmin");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
