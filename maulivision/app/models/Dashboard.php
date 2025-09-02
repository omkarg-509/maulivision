<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getVendorCount()
    {
        $stmt = $this->db2->prepare("SELECT COUNT(*) AS cnt FROM vendor");
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return (int)($row['cnt'] ?? 0);
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
