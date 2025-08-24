<?php

require_once __DIR__ . '/../../core/Database.php';

class User extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findByEmailOrNumber($identifier)
    {
        $identifier = trim($identifier);
        if ($identifier === '') return null;

        // Try common columns on users table
        $sql = "SELECT * FROM users WHERE email = ? OR mobile = ? OR number = ? LIMIT 1";
        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('sss', $identifier, $identifier, $identifier);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $row = $result ? $result->fetch_assoc() : null;
                $stmt->close();
                if ($row) return $row;
            } else {
                $stmt->close();
            }
        }

        // Try vendors table as fallback (since app uses vendor session)
        $sql2 = "SELECT * FROM vendors WHERE email = ? OR mobile = ? OR number = ? LIMIT 1";
        if ($stmt2 = $this->db->prepare($sql2)) {
            $stmt2->bind_param('sss', $identifier, $identifier, $identifier);
            if ($stmt2->execute()) {
                $result2 = $stmt2->get_result();
                $row2 = $result2 ? $result2->fetch_assoc() : null;
                $stmt2->close();
                if ($row2) return $row2;
            } else {
                $stmt2->close();
            }
        }

        return null;
    }

    public function findById($id)
    {
        $id = (int)$id;
        if ($id <= 0) return null;

        // users table first
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $row = $result ? $result->fetch_assoc() : null;
                $stmt->close();
                if ($row) return $row;
            } else {
                $stmt->close();
            }
        }

        // fallback vendors table
        $sql2 = "SELECT * FROM vendors WHERE id = ? LIMIT 1";
        if ($stmt2 = $this->db->prepare($sql2)) {
            $stmt2->bind_param('i', $id);
            if ($stmt2->execute()) {
                $result2 = $stmt2->get_result();
                $row2 = $result2 ? $result2->fetch_assoc() : null;
                $stmt2->close();
                if ($row2) return $row2;
            } else {
                $stmt2->close();
            }
        }

        return null;
    }
}
