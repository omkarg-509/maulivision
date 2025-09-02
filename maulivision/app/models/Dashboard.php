<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getSuperAdminCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM superadmin");
        if (!$stmt) {
            return 0;
        }
        if (!$stmt->execute()) {
            $stmt->close();
            return 0;
        }

        // Prefer get_result when available
        if (method_exists($stmt, 'get_result')) {
            $res = $stmt->get_result();
            if ($res) {
                $row = $res->fetch_assoc();
                $stmt->close();
                return (int)($row['cnt'] ?? $row['count'] ?? 0);
            }
        }

        // Fallback to bind_result + fetch (portable)
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();
        return (int)($cnt ?? 0);
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
