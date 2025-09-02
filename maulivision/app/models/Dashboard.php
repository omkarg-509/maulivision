<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function getSuperAdminCount(): int
    {
        $sql = "SELECT COUNT(*) AS cnt FROM superadmin";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return 0;
        }

        // No params to bind here
        if (!$stmt->execute()) {
            $stmt->close();
            return 0;
        }

        // Correct way with mysqli: bind result, then fetch()
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();

        return (int)($cnt ?? 0);
    }

 
}
