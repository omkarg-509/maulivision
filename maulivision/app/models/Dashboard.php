<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    // Declare inherited properties for static analysis clarity
    protected $db;
    protected $db2;

    public function countSuperAdmins()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM superadmin");
        if (!$stmt) {
            return 0;
        }
        $stmt->execute();
        // Prefer get_result when available (mysqlnd), else fallback to bind_result
        if (method_exists($stmt, 'get_result')) {
            $result = $stmt->get_result();
            if ($result) {
                $row = $result->fetch_assoc();
                return isset($row['total']) ? (int)$row['total'] : 0;
            }
            return 0;
        }
        $total = 0;
        $stmt->bind_result($total);
        $stmt->fetch();
        return (int)$total;
    }

    public function vendorCount()
    {
    $stmt = $this->db2->prepare("SELECT COUNT(*) AS total FROM vendor");
        if (!$stmt) {
            return 0;
        }
        $stmt->execute();
        if (method_exists($stmt, 'get_result')) {
            $result = $stmt->get_result();
            if ($result) {
                $row = $result->fetch_assoc();
                return isset($row['total']) ? (int)$row['total'] : 0;
            }
            return 0;
        }
        $total = 0;
        $stmt->bind_result($total);
        $stmt->fetch();
        return (int)$total;
    }

    public function connectionStatus(): array
    {
        return [
            'db' => $this->db instanceof mysqli ? (bool)$this->db->ping() : false,
            'db2' => $this->db2 instanceof mysqli ? (bool)$this->db2->ping() : false,
        ];
    }
}
