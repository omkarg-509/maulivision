<?php
require_once '../core/Database.php';

class LaundryOrder extends Database
{
    public function ensureTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS lms_orders (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            customer_id INT UNSIGNED NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_cust (customer_id),
            CONSTRAINT fk_laundry_order_customer FOREIGN KEY (customer_id)
              REFERENCES lms_customers(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->db->query($sql);
        if ($this->db->error) {
            die('Orders table error: ' . $this->db->error);
        }
    }

    public function insert(array $data): int
    {
        $this->ensureTable();
        $stmt = $this->db->prepare("INSERT INTO lms_orders (customer_id, start_date, end_date) VALUES (?, ?, ?)");
        if (!$stmt) { die('Orders prepare error: ' . $this->db->error); }
        $stmt->bind_param('iss', $data['customer_id'], $data['start_date'], $data['end_date']);
        $stmt->execute();
        if ($stmt->error) { die('Orders insert error: ' . $stmt->error); }
        $id = $this->db->insert_id;
        $stmt->close();
        return (int)$id;
    }

    public function findLatestByCustomer(int $customerId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM lms_orders WHERE customer_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param('i', $customerId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}
