<?php
return new class {
    public function up($db){
        $sql = "CREATE TABLE IF NOT EXISTS vendors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_id INT NOT NULL,
            full_name VARCHAR(150) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            address TEXT NULL,
            business_name VARCHAR(200) NOT NULL,
            business_role VARCHAR(120) NULL,
            business_number VARCHAR(50) NULL,
            business_address TEXT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_admin_phone (admin_id, phone),
            INDEX idx_admin_status (admin_id, status),
            CONSTRAINT fk_vendors_admin FOREIGN KEY (admin_id) REFERENCES superadmin(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $db->query($sql);
    }
    public function down($db){ $db->query("DROP TABLE IF EXISTS vendors"); }
};
?>