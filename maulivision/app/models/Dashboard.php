<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
        public function vendorCount()
    {
        $stmt = $this->db2->prepare("SELECT COUNT(*) as total FROM vendor");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
}
