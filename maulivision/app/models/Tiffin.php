<?php
require_once '../core/Database.php';

class Tiffin extends Database
{
    protected $db;

    public function list($adminId, $from=null, $to=null)
    {
        $params=[$adminId]; $types='i'; $filter='';
        if($from && $to){ $filter=' AND entry_date BETWEEN ? AND ?'; $params[]=$from; $params[]=$to; $types.='ss'; }
        $sql="SELECT id, entry_date, tiffin_time, quantity, rate, paid, (quantity*rate) total, created_at FROM tiffin_entries WHERE admin_id=?$filter ORDER BY entry_date DESC, id DESC";
        $stmt=$this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute(); $res=$stmt->get_result(); $rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r; return $rows;
    }

    public function create($adminId,$date,$time,$qty,$rate,$paid)
    {
        $stmt=$this->db->prepare("INSERT INTO tiffin_entries (admin_id,entry_date,tiffin_time,quantity,rate,paid) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('issidi',$adminId,$date,$time,$qty,$rate,$paid);
        if($stmt->execute()) return (int)$this->db->insert_id; return 0;
    }

    public function delete($id,$adminId)
    {
        $stmt=$this->db->prepare("DELETE FROM tiffin_entries WHERE id=? AND admin_id=?");
        $stmt->bind_param('ii',$id,$adminId); return $stmt->execute();
    }

    public function togglePaid($id,$adminId)
    {
        $stmt=$this->db->prepare("UPDATE tiffin_entries SET paid = NOT paid WHERE id=? AND admin_id=?");
        $stmt->bind_param('ii',$id,$adminId); return $stmt->execute();
    }

    public function dailyStats($adminId,$from,$to)
    {
        if(!$from){ $from=date('Y-m-d', strtotime('-7 days')); }
        if(!$to){ $to=date('Y-m-d'); }
        $sql="SELECT entry_date day,
              SUM(quantity) total_qty,
              SUM(quantity*rate) total_amount,
              SUM(CASE WHEN paid=1 THEN quantity*rate ELSE 0 END) paid_amount,
              SUM(CASE WHEN paid=0 THEN quantity*rate ELSE 0 END) unpaid_amount
            FROM tiffin_entries WHERE admin_id=? AND entry_date BETWEEN ? AND ? GROUP BY day ORDER BY day ASC";
        $stmt=$this->db->prepare($sql);
        $stmt->bind_param('iss',$adminId,$from,$to);
        $stmt->execute(); $res=$stmt->get_result(); $rows=[]; while($r=$res->fetch_assoc()){ $rows[]=[
            'day'=>$r['day'],
            'total_qty'=>(int)$r['total_qty'],
            'total_amount'=>(float)$r['total_amount'],
            'paid_amount'=>(float)$r['paid_amount'],
            'unpaid_amount'=>(float)$r['unpaid_amount']
        ]; }
        return $rows;
    }

    public function summary($adminId,$from,$to)
    {
        if(!$from){ $from=date('Y-m-d', strtotime('-7 days')); }
        if(!$to){ $to=date('Y-m-d'); }
        $sql="SELECT SUM(quantity) qty,
              SUM(quantity*rate) total_amt,
              SUM(CASE WHEN paid=1 THEN quantity*rate ELSE 0 END) paid_amt,
              SUM(CASE WHEN paid=0 THEN quantity*rate ELSE 0 END) unpaid_amt
            FROM tiffin_entries WHERE admin_id=? AND entry_date BETWEEN ? AND ?";
        $stmt=$this->db->prepare($sql);
        $stmt->bind_param('iss',$adminId,$from,$to);
        $stmt->execute(); $r=$stmt->get_result()->fetch_assoc();
        return [
            'qty'=>(int)($r['qty']??0),
            'total'=>(float)($r['total_amt']??0),
            'paid'=>(float)($r['paid_amt']??0),
            'unpaid'=>(float)($r['unpaid_amt']??0)
        ];
    }
}
?>