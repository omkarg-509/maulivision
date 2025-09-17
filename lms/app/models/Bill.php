<?php
require_once '../core/Database.php';

class Bill extends Database
{
    public function ensureTables(): void
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS lms_bills (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            customer_id INT UNSIGNED NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_cust (customer_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

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

    public function create(int $customerId, float $total): int
    {
        $this->ensureTables();
        $stmt = $this->db->prepare("INSERT INTO lms_bills (customer_id,total_amount) VALUES (?,?)");
        $stmt->bind_param('id', $customerId, $total);
        $stmt->execute();
        $id = (int)$this->db->insert_id;
        $stmt->close();
        return $id;
    }

    public function all(): array
    {
        $sql = "SELECT b.*, c.customer_name, c.phone_number
                FROM lms_bills b
                JOIN lms_customers c ON c.id=b.customer_id
                ORDER BY b.id DESC";
        $res = $this->db->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT b.*, c.customer_name, c.phone_number
                                     FROM lms_bills b
                                     JOIN lms_customers c ON c.id=b.customer_id
                                     WHERE b.id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}
