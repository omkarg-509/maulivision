<?php

require_once '../core/Database.php';

class Dashboard extends Database
{
 public function getAll()
    {
    
            $stmt = $this->db2->prepare("SELECT * FROM vendor");
          $stmt->execute();
        $result = $stmt->get_result();
        $vendor = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $vendor;
    }
}
