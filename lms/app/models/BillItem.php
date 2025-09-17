<?php
require_once '../core/Database.php';

class BillItem extends Database
{
    public function ensureTables(): void
    {
        // ensured by Bill::ensureTables(); kept for safety if used directly
        $this->db->query("CREATE TABLE IF NOT EXISTS lms_bill_items (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            bill_id INT UNSIGNED NOT NULL,
            item_name VARCHAR(191) NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            weight DECIMAL(10,2) NULL,
            price DECIMAL(10,2) NOT NULL DEFAULT 0,
            INDEX idx_bill (bill_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public function create(int $billId, string $name, int $qty, ?float $weight, float $price): int
    {
        $this->ensureTables();
        $stmt = $this->db->prepare("INSERT INTO lms_bill_items (bill_id,item_name,quantity,weight,price) VALUES (?,?,?,?,?)");
        // weight can be null
        $w = isset($weight) ? $weight : null;
        $stmt->bind_param('isidd', $billId, $name, $qty, $w, $price);
        $stmt->execute();
        $id = (int)$this->db->insert_id;
        $stmt->close();
        return $id;
    }

    public function forBill(int $billId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM lms_bill_items WHERE bill_id=? ORDER BY id ASC");
        $stmt->bind_param('i', $billId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
