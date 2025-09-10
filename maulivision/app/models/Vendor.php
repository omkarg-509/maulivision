<?php
require_once '../core/Database.php';

class Vendor extends Database
{
    protected $db;

    public function listByAdmin($adminId)
    {
        $stmt = $this->db->prepare("SELECT id, full_name, phone, business_name, business_role, status, created_at FROM vendors WHERE admin_id=? ORDER BY id DESC");
        $stmt->bind_param('i',$adminId); $stmt->execute(); $res=$stmt->get_result(); $rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r; return $rows;
    }

    public function create($adminId,$fullName,$phone,$address,$bName,$bRole,$bNumber,$bAddress)
    {
        $stmt=$this->db->prepare("INSERT INTO vendors (admin_id,full_name,phone,address,business_name,business_role,business_number,business_address) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('isssssss',$adminId,$fullName,$phone,$address,$bName,$bRole,$bNumber,$bAddress);
        if($stmt->execute()) return (int)$this->db->insert_id; return 0;
    }

    public function toggleStatus($id,$adminId)
    {
        $stmt=$this->db->prepare("UPDATE vendors SET status = IF(status='active','inactive','active') WHERE id=? AND admin_id=?");
        $stmt->bind_param('ii',$id,$adminId); return $stmt->execute();
    }

    public function countByAdmin($adminId)
    {
        $stmt=$this->db->prepare("SELECT COUNT(*) c FROM vendors WHERE admin_id=?");
        $stmt->bind_param('i',$adminId); $stmt->execute(); $res=$stmt->get_result()->fetch_assoc(); return (int)$res['c'];
    }
}
?>