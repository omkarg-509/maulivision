<?php 
require_once '../core/Database.php';

class DailyEntry extends Database
{

     
    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM customers");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($data)
    {
        $stmt = $this->db->prepare("INSERT INTO customers (vid,name,mobile,in_time,amount) VALUES (?,?,?,?,?)");
        $stmt->bind_param("isssd", $data['vid'], $data['name'], $data['mobile'], $data['in_time'], $data['amount']);
        $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}