<?php

require_once '../core/Database.php';

class Customer extends Database
{


//  public function countDailyEntry()
//     {
//         $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM daily_entries WHERE DATE(created_at) = CURDATE()");
//         $stmt->execute();
//         $result = $stmt->get_result();
//         return $result->fetch_assoc()['total'];
//     }

//     public function countAll()
//     {
//         $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM customers");
//         $stmt->execute();
//         $result = $stmt->get_result();
//         return $result->fetch_assoc()['total'];
//     }


    public function getByBillId($id)
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, d.* 
             FROM customers c
             LEFT JOIN daily_entries d ON c.id = d.cid
             WHERE c.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function DailyEntries($vid, $customerId)
    {
        $stmt = $this->db->prepare("SELECT * FROM daily_entries WHERE vid = 1 AND cid = 38");
        $stmt->bind_param("ii", $vid, $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE d_status = ? ORDER BY id DESC");
        $d_status = '0';
        $stmt->bind_param("s", $d_status);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($data)
    {
        $stmt = $this->db->prepare("INSERT INTO customers (vid,bill_id,name, mobile, address) VALUES (?,?,?,?,?)");
        $stmt->bind_param("issss", $data['vid'], $data['bill_id'], $data['name'], $data['mobile'], $data['address']);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


     public function delete($id)
{
    $stmt = $this->db->prepare("UPDATE customers SET d_status = '1' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
    }

public function searchByTerm($term)
{   
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
$vendor = isset($_SESSION['vendor']['vid']) ? $_SESSION['vendor']['vid'] : 0;
echo "Vendor: " . $vendor . "<br>"; // Print vendor
$term = "%{$term}%";
$stmt = $this->db->prepare(
    "SELECT vid,id, bill_id, name, mobile 
     FROM customers 
     WHERE (name LIKE ? OR bill_id LIKE ? OR mobile LIKE ? OR id LIKE ?)
     AND d_status = '0' AND vid = ?"
);
$stmt->bind_param("ssssi", $term, $term, $term, $term, $vendor);
$stmt->execute();
$result = $stmt->get_result();

$customers = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}
return $customers;
}

public function getById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM customers WHERE  id = ? ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // इथे एकाच customer चा data येतो
}


public function updateData($id, $data)
{
    $stmt = $this->db->prepare("UPDATE customers SET name = ?, mobile = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $data['name'], $data['mobile'], $data['address'], $id);
    $stmt->execute();   
    return $stmt->affected_rows > 0;
}
// public function getDailyEntries($vid, $cid)
// {
//     $stmt = $this->db->prepare("SELECT * FROM daily_entries WHERE DATE(created_at) = CURDATE() AND vid = ? AND cid = ?");
//     $stmt->bind_param("ii", $vid, $cid);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     return $result->fetch_all(MYSQLI_ASSOC);
// }
public function getDailyEntries($vid, $cid)
{
    $stmt = $this->db->prepare("SELECT * FROM daily_entries WHERE vid = ? AND cid = ?");
    $stmt->bind_param("ii", $vid, $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); // Array return करायला पाहिजे
}

    
}
