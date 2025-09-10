<?php
return new class {
    public function up($db){
        $sql = "CREATE TABLE IF NOT EXISTS tiffin_entries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_id INT NOT NULL,
            entry_date DATE NOT NULL,
            tiffin_time VARCHAR(20) NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            rate DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            paid TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_admin_date (admin_id, entry_date),
            INDEX idx_paid (paid),
            CONSTRAINT fk_tiffin_admin FOREIGN KEY (admin_id) REFERENCES superadmin(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $db->query($sql);
    }
    public function down($db){
        $db->query("DROP TABLE IF EXISTS tiffin_entries");
    }
};
?>