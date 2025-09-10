<?php
require_once '../core/Database.php';

class Todo extends Database
{
    protected $db;

    public function allByAdmin($adminId)
    {
        $stmt = $this->db->prepare("SELECT id, title, is_done, created_at FROM todos WHERE admin_id = ? ORDER BY id DESC");
        $stmt->bind_param('i', $adminId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function create($adminId, $title)
    {
        $stmt = $this->db->prepare("INSERT INTO todos (admin_id, title, is_done, created_at) VALUES (?, ?, 0, NOW())");
        $stmt->bind_param('is', $adminId, $title);
        return $stmt->execute();
    }

    public function toggle($id, $adminId)
    {
        $stmt = $this->db->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = ? AND admin_id = ?");
        $stmt->bind_param('ii', $id, $adminId);
        return $stmt->execute();
    }

    public function delete($id, $adminId)
    {
        $stmt = $this->db->prepare("DELETE FROM todos WHERE id = ? AND admin_id = ?");
        $stmt->bind_param('ii', $id, $adminId);
        return $stmt->execute();
    }
}
