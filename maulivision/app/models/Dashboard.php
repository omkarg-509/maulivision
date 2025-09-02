<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getSuperAdminCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM superadmin");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int) $row['count']; // फक्त integer परत करतो
    }

    public function getVendors()
    {
        $stmt = $this->db->prepare("SELECT id, name, email FROM vendors ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $vendors = [];
        while ($row = $result->fetch_assoc()) {
            $vendors[] = $row;
        }
        return $vendors;
    }
}
