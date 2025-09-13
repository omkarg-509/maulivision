<?php 
require_once '../core/Database.php';

class Customers extends Database
{

     
    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM mcms_customers ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($data)
    {
        $stmt = $this->db->prepare("INSERT INTO mcms_customers ORDER BY id DESC (vid,name,mobile,in_time,amount,staff,payment_method) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("isssdss", $data['vid'], $data['name'], $data['mobile'], $data['in_time'], $data['amount'],$data['staff'] ,$data['payment_method']);
        $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM mcms_customers ORDER BY id DESC WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}