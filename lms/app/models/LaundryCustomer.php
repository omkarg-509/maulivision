<?php
require_once '../core/Database.php';

class LaundryCustomer extends Database
{
    public function ensureTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS lms_customers (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(191) NOT NULL,
            phone_number VARCHAR(20) NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->db->query($sql);
    }

    public function insert(array $data): int
    {
        $this->ensureTable();
        $stmt = $this->db->prepare("INSERT INTO lms_customers (customer_name, phone_number) VALUES (?, NULLIF(?, ''))");
        $stmt->bind_param('ss', $data['customer_name'], $data['phone_number']);
        $stmt->execute();
        $id = $this->db->insert_id;
        $stmt->close();
        return (int)$id;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM lms_customers WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function getAllWithOrderDates(): array
    {
        $sql = "SELECT c.id, c.customer_name, c.phone_number, o.start_date, o.end_date
                FROM lms_customers c
                LEFT JOIN lms_orders o ON o.customer_id = c.id
                AND o.id = (SELECT MAX(id) FROM lms_orders WHERE customer_id = c.id)
                ORDER BY c.id DESC";
        $res = $this->db->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
