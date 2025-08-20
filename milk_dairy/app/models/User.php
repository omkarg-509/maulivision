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

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO vendor (name, email, password, mobile_number, business_name, business_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssss",
            $data['name'],
            $data['email'],
            $data['password'],
            $data['mobile_number'],
            $data['business_name'],
            $data['business_number']
        );
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function findByEmailAndNumber($email, $number)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor WHERE email = ? AND mobile_number = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updatePassword($id, $new_password)
    {
        $stmt = $this->db->prepare("UPDATE vendor SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $id);
        return $stmt->execute();
    }

    public function findByEmailOrNumber($email_or_number)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor WHERE email = ? OR mobile_number = ? LIMIT 1");
        $stmt->bind_param("ss", $email_or_number, $email_or_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
