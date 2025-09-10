<?php
/**
 * Migration: Create todos table
 */
return new class {
    public function up(mysqli $db)
    {
        $sql = "CREATE TABLE IF NOT EXISTS todos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            is_done TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (admin_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        if(!$db->query($sql)) {
            throw new Exception('Failed creating todos table: ' . $db->error);
        }
    }

    public function down(mysqli $db)
    {
        $db->query("DROP TABLE IF EXISTS todos");
    }
};
