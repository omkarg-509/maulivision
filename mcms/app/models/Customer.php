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


    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM customers ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

        public function history($date)
        {
            $stmt = $this->db->prepare("SELECT * FROM customers WHERE created_at = ?");
            $stmt->bind_param("s", $date);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
    public function insert($data)
    {
        $stmt = $this->db->prepare("INSERT INTO customers (vid,name, mobile, address) VALUES (?,?, ?, ?)");
        $stmt->bind_param("isss",$data['vid'], $data['name'], $data['mobile'], $data['address']);
        $stmt->execute();
    }

   
   

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

public function searchByTerm($term)
{
    $term = "%{$term}%";
    $stmt = $this->db->prepare("SELECT id, name, mobile FROM customers WHERE name LIKE ? OR mobile LIKE ? OR id LIKE ?");
    $stmt->bind_param("sss", $term, $term, $term);
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
