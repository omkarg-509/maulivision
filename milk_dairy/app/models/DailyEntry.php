<?php 
require_once '../core/Database.php';

class DailyEntry extends Database
{

     
    public function getAll($vid = null)
    {
        if ($vid !== null) {
            $stmt = $this->db->prepare("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.cid = customers.id WHERE daily_entries.vid = ? ORDER BY daily_entries.id DESC");
            $stmt->bind_param("i", $vid);
            $stmt->execute();
            $result = $stmt->get_result();
            $entries = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $entries;
        } else {
            $result = $this->db->query("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.cid = customers.id ORDER BY daily_entries.id DESC");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function insert($data)
    {
        $stmt = $this->db->prepare("INSERT INTO daily_entries (vid,cid,milktype,milkliter) VALUES (?,?,?,?)");
        $stmt->bind_param("iisd", $data['vid'], $data['cid'], $data['milktype'], $data['milkliter']);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM daily_entries WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getByBillId($bill_id)
    {
        $stmt = $this->db->prepare("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.cid = customers.id WHERE customers.bill_id = ?");
        $stmt->bind_param("s", $bill_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $entry = $result->fetch_assoc();
        $stmt->close();
        return $entry;
    }
}