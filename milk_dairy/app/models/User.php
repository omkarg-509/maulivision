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
        // Align columns with DB schema order
        $stmt = $this->db->prepare("INSERT INTO vendor (name, business_name, business_number, business_address, email, mobile_number, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssss",
            $data['name'],
            $data['business_name'],
            $data['business_number'],
            $data['business_address'],
            $data['email'],
            $data['mobile_number'],
            $data['password']
        );
        if ($stmt->execute()) {
            $id = $this->db->insert_id;
            $stmt->close();
            return $id;
        }
        $stmt->close();
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

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM vendor WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function fetchLngByVid($vid)
    {
        $stmt = $this->db->prepare("SELECT lng FROM vendor WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $vid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['lng'] : null;
    }
}
