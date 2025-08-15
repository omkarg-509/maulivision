<?php

require_once '../core/Database.php';

class User extends Database
{
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
     public function register($data)
    {
        $stmt = $this->db->prepare("INSERT INTO vendor (name,username,password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['name'], $data['username'], $data['password']);
        return $stmt->execute();
    }
}
