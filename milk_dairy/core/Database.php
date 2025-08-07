<?php

class Database
{
    protected $db;

    public function __construct()
    {
        $this->db = new mysqli("localhost", "u367009900_milk_dairy", "AC]WO/mL9", "u367009900_milk_dairy");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    
}

