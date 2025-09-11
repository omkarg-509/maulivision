<?php

require_once '../core/Database.php';

class User extends Database
{
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendors WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO vendors (full_name,phone,email,username,password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['full_name'], $data['phone'], $data['email'], $data['username'], $data['password']);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

}
