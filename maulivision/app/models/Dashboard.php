<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
    public function fetchSuperAdminData(): array
    {
        $sql = "SELECT * FROM superadmin";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return [];
        }

        if (!$stmt->execute()) {
            if ($stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
            return [];
        }

        $result = $stmt->get_result();
        if (!$result) {
            $stmt->close();
            return [];
        }
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $data;
    }
}
