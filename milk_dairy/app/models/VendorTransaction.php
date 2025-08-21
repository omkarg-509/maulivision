<?php
require_once '../core/Database.php';

class VendorTransaction extends Database
{
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO vendor_transactions (transaction_id, vendor_id, amount, transaction_date, transaction_type, status, reference, remarks, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,NOW(),NOW())");
        $stmt->bind_param('sidsssss', $data['transaction_id'], $data['vendor_id'], $data['amount'], $data['transaction_date'], $data['transaction_type'], $data['status'], $data['reference'], $data['remarks']);
        $ok = $stmt->execute();
        $id = $ok ? $this->db->insert_id : false;
        $stmt->close();
        return $id;
    }
}
