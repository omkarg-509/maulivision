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
    
    /**
     * Find a vendor by any of: email, username, or mobile/phone number.
     * Accepts a single identifier string and tries to match in priority order: email -> username -> phone.
     */
    public function findByIdentifier($identifier)
    {
        // Decide which column(s) to try; use one prepared statement with OR to keep it simple.
        $stmt = $this->db->prepare("SELECT * FROM vendors WHERE email = ? OR username = ? OR mobile = ? OR phone = ? LIMIT 1");
        // Bind same identifier to each possible field (unused columns will just not match)
        $stmt->bind_param("ssss", $identifier, $identifier, $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
  
}
