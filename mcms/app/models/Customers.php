<?php 
require_once '../core/Database.php';

class Customers extends Database
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    // Create table if missing
    private function ensureTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS mcms_customers (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            vid INT UNSIGNED NOT NULL,
            name VARCHAR(191) NOT NULL,
            mobile VARCHAR(20) NULL,
            in_time TIME NOT NULL,
            amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            staff VARCHAR(100) NOT NULL,
            payment_method VARCHAR(50) NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_vid_created (vid, created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->db->query($sql);
    }

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM mcms_customers ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO mcms_customers (vid,name,mobile,in_time,amount,staff,payment_method) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "isssdss",
            $data['vid'],
            $data['name'],
            $data['mobile'],
            $data['in_time'],
            $data['amount'],
            $data['staff'],
            $data['payment_method']
        );
        $stmt->execute();
        $newId = $this->db->insert_id;
        $stmt->close();
        return $newId;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM mcms_customers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }
}