<?php
return new class {
    public function up($db){
        // Try drop previous FK referencing admins if exists
        @$db->query("ALTER TABLE finance_entries DROP FOREIGN KEY fk_finance_admin");
        // Recreate with correct reference to superadmin
        $db->query("ALTER TABLE finance_entries ADD CONSTRAINT fk_finance_admin FOREIGN KEY (admin_id) REFERENCES superadmin(id) ON DELETE CASCADE");
    }
    public function down($db){
        // Best effort revert (drops FK)
        @$db->query("ALTER TABLE finance_entries DROP FOREIGN KEY fk_finance_admin");
    }
};
?>