<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
        public function vendorCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM superadmin");
        if (!$stmt) {
            return 0;
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['total']) ? (int)$row['total'] : 0;
        }
        return 0;
    }
}
