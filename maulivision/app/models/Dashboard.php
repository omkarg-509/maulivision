<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getVendorCount($adminId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vendors WHERE admin_id=?");
        $stmt->bind_param('i',$adminId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return (int)($row['cnt'] ?? 0);
    }

    public function recentVendors($adminId, $limit = 5)
    {
        $stmt = $this->db->prepare("SELECT id, full_name, phone, business_name, status FROM vendors WHERE admin_id=? ORDER BY id DESC LIMIT ?");
        $stmt->bind_param('ii',$adminId,$limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $vendors = [];
        while ($row = $result->fetch_assoc()) {
            $vendors[] = $row;
        }
        return $vendors;
    }
}
