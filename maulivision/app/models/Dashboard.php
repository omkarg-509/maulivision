<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function vendorCount()
    {
        // Check if db2 connection exists and is successful
        if (!isset($this->db2) || !$this->db2) {
            return 0;
        }

        // Use db2 (milk_dairy) and the vendor table
        $stmt = $this->db2->prepare("SELECT COUNT(*) AS total FROM vendor");
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
