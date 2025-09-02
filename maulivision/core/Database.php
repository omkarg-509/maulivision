<?php

class Database
{
    protected $db;

    public function __construct()
    {
        $this->db = new mysqli("localhost", "u367009900_maulivision", "", "u367009900_maulivision");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

  
}

