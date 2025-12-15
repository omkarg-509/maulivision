<?php 
require_once '../core/Database.php';

class Customers extends Database
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }
    public function dailyEarning(): float
    {
        $stmt = $this->db->prepare("SELECT SUM(amount) as total FROM mcms_customers WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['total'] ?? null;
        return (float)($total ?? 0.0);
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

    public function getByVendor(int $vid): array
    {
        $stmt = $this->db->prepare("SELECT * FROM mcms_customers WHERE vid = ? ORDER BY created_at DESC, id DESC");
        $stmt->bind_param("i", $vid);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

            // Get unique customers by mobile for a vendor (latest row per mobile). Excludes null/empty mobile.
        public function getByVendorUniqueMobile(int $vid): array
        {
            // Subquery picks latest id per mobile for the vendor where mobile is not empty/null
            $sql = "
                SELECT c.* FROM mcms_customers c
                INNER JOIN (
                    SELECT mobile, MAX(id) AS max_id
                    FROM mcms_customers
                    WHERE vid = ? AND mobile IS NOT NULL AND mobile <> ''
                    GROUP BY mobile
                ) AS t ON t.max_id = c.id
                    WHERE c.vid = ?
                    ORDER BY c.created_at DESC, c.id DESC";

                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ii", $vid, $vid);
            $stmt->execute();
            $res = $stmt->get_result();
            $rows = $res->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

      public function create($vid,$name,$mobile,$in_time,$amount,$staff,$payment_method)
    {
        $stmt=$this->db->prepare("INSERT INTO mcms_customers (vid,name,mobile,in_time,amount,staff,payment_method) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('isssdss',$vid,$name,$mobile,$in_time,$amount,$staff,$payment_method);
        if($stmt->execute()) return (int)$this->db->insert_id; return 0;
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