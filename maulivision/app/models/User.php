<?php

require_once '../core/Database.php';

class User extends Database
{

    public function findByEmailOrNumber($email_or_number)
    {
        $stmt = $this->db->prepare("SELECT * FROM superadmin WHERE email = ? OR mobile = ? LIMIT 1");
        $stmt->bind_param("ss", $email_or_number, $email_or_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}