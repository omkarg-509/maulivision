<?php 
require_once '../core/Database.php';

class DailyEntry extends Database
{

     
    public function getAll()
    {
        $result = $this->db->query("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.cid = customers.id ORDER BY daily_entries.id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($data)
    {
        $stmt = $this->db->prepare("INSERT INTO daily_entries (vid,cid,milktype,milkliter) VALUES (?,?,?,?)");
        $stmt->bind_param("iiss", $data['vid'], $data['cid'], $data['milktype'], $data['milkliter']);
        $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM daily_entries WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}