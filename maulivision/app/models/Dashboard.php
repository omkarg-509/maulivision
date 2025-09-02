<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
        public function countVendorModels()
        {
            $stmt = $this->db2->prepare("SELECT COUNT(*) as total FROM vendor");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        }
}
