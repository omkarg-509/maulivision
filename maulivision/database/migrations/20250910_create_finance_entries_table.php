<?php
return new class {
    public function up($db) {
        $sql = "CREATE TABLE IF NOT EXISTS finance_entries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_id INT NOT NULL,
            type ENUM('income','expense','borrow','repay') NOT NULL,
            method ENUM('cash','online') NOT NULL DEFAULT 'cash',
            amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            note TEXT NULL,
            entry_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_admin_date (admin_id, entry_date),
            INDEX idx_type (type),
            INDEX idx_method (method),
            CONSTRAINT fk_finance_admin FOREIGN KEY (admin_id) REFERENCES superadmin(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $db->query($sql);
    }

    public function down($db) {
        $db->query("DROP TABLE IF EXISTS finance_entries");
    }
};
?>