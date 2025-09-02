<?php

class Database
{
    protected $db;
    protected $db2;

    public function __construct()
    {
        $this->db = new mysqli("localhost", "u367009900_maulivision", "1r4kPtXJo@", "u367009900_maulivision");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }

        $this->db2 = new mysqli("localhost", "u367009900_milk_dairy", "AC]WO/mL9", "u367009900_milk_dairy");
    if ($this->db2->connect_error) {
        die("Database milk_dairy connection failed: " . $this->db2->connect_error);
    }
    }
}