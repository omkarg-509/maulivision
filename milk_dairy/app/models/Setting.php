<?php
require_once '../core/Database.php';

class Setting extends Database
{
    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM settings ORDER BY id ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function setActive($id)
    {
        // Reset others
        $this->db->query("UPDATE settings SET status = 0");
        $stmt = $this->db->prepare("UPDATE settings SET status = 1, updated_at = ? WHERE id = ?");
        $now = date('Y-m-d H:i:s');
        $stmt->bind_param('si', $now, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM settings WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }
}
