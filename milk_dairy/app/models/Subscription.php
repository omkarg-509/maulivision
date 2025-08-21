<?php
require_once '../core/Database.php';

class Subscription extends Database
{
    public function getActiveByVendor($vendorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM subscriptions WHERE vendor_id=? AND status='active' AND end_date >= CURDATE() ORDER BY end_date DESC LIMIT 1");
        $stmt->bind_param('i', $vendorId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $res;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO subscriptions (vendor_id, plan_name, start_date, end_date, status, amount, auto_renew, created_at, updated_at) VALUES (?,?,?,?,?,?,?,NOW(),NOW())");
        $autoRenew = isset($data['auto_renew']) ? (int)$data['auto_renew'] : 0;
        $stmt->bind_param('issssdi', $data['vendor_id'], $data['plan_name'], $data['start_date'], $data['end_date'], $data['status'], $data['amount'], $autoRenew);
        $ok = $stmt->execute();
        $id = $ok ? $this->db->insert_id : false;
        $stmt->close();
        return $id;
    }
}
