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
        if($stmt->execute()) {
            return (int)$this->db->insert_id;
        }
        return 0;
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

    public function getDailyStats($adminId, $fromDate, $toDate)
    {
        // Default date range if missing
        if(!$fromDate){ $fromDate = date('Y-m-d', strtotime('-7 days')); }
        if(!$toDate){ $toDate = date('Y-m-d'); }
        $sql = "SELECT DATE(created_at) as day, COUNT(*) as total, SUM(is_done=1) as done
                FROM todos
                WHERE admin_id = ? AND DATE(created_at) BETWEEN ? AND ?
                GROUP BY day
                ORDER BY day ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iss', $adminId, $fromDate, $toDate);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while($row = $res->fetch_assoc()) {
            $rows[] = [
                'day' => $row['day'],
                'total' => (int)$row['total'],
                'done' => (int)$row['done']
            ];
        }
        return $rows;
    }
}
