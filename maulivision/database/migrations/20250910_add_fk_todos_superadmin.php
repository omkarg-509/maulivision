<?php
return new class {
    public function up($db){
        // Ensure column exists and add FK if not present
        // Try adding index if not exists (suppressed errors)
        @$db->query("ALTER TABLE todos ADD INDEX idx_todos_admin (admin_id)");
        // Add constraint (ignore if duplicate)
        @$db->query("ALTER TABLE todos ADD CONSTRAINT fk_todos_admin FOREIGN KEY (admin_id) REFERENCES superadmin(id) ON DELETE CASCADE");
    }
    public function down($db){
        @$db->query("ALTER TABLE todos DROP FOREIGN KEY fk_todos_admin");
    }
};
?>