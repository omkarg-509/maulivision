<?php
/**
 * Seeder: Insert sample todos for admin id 1 (idempotent)
 */
return new class {
    public function run(mysqli $db)
    {
        // Check if any todos exist for admin 1 already
        $check = $db->query("SELECT id FROM todos WHERE admin_id = 1 LIMIT 1");
        if ($check && $check->num_rows > 0) {
            return; // Already seeded
        }
        $stmt = $db->prepare("INSERT INTO todos (admin_id, title, is_done, created_at) VALUES (1, ?, 0, NOW())");
        $samples = [
            'Review project dashboard',
            'Plan next feature',
            'Refactor controller logic'
        ];
        foreach($samples as $title){
            $stmt->bind_param('s', $title);
            $stmt->execute();
        }
    }
};
