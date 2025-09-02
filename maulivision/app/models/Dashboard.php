<?php

require_once '../core/Database.php';

class Customer extends Database
{
        public function countVendorModels()
        {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM vendor");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        }
}
