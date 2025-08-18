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


    public function getByBillId($bill_id)
    {
        $stmt = $this->db->prepare("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.vid = customers.id WHERE customers.bill_id = ?");
        $stmt->bind_param("i", $bill_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM customers ORDER BY id DESC");
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
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

public function searchByTerm($term)
{
    $term = "%{$term}%";
    $stmt = $this->db->prepare("SELECT id,bill_id, name, mobile FROM customers WHERE name LIKE ? OR bill_id LIKE ? OR mobile LIKE ? OR id LIKE ?");
    $stmt->bind_param("ssss", $term, $term, $term, $term);
    $stmt->execute();
    $result = $stmt->get_result();

    $customers = [];
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
    return $customers;
}

public function getById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
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
