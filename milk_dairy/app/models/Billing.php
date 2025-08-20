<?php
require_once '../core/Database.php';
class Billing extends Database
{
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO billing (customer, amount, date, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $data['customer'], $data['amount'], $data['date'], $data['description']);
        return $stmt->execute();
    }
}
