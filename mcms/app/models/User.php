<?php

require_once '../core/Database.php';

class User extends Database
{
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
