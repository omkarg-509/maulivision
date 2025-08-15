<?php

class Database
{
    protected $db;

    public function __construct()
    {
        $this->db = new mysqli("localhost", "u367009900_mcms", "5P[lKbV^^v&", "u367009900_mcms");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    
}

