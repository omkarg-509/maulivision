<?php

class Database
{
    protected $db;
    protected $db2;

    public function __construct()
    {
        // Allow overrides via environment variables for portability
        $db1Host = getenv('DB1_HOST') ?: 'localhost';
        $db1User = getenv('DB1_USER') ?: 'u367009900_maulivision';
        $db1Pass = getenv('DB1_PASS') ?: '1r4kPtXJo@';
        $db1Name = getenv('DB1_NAME') ?: 'u367009900_maulivision';

        $db2Host = getenv('DB2_HOST') ?: 'localhost';
        $db2User = getenv('DB2_USER') ?: 'u367009900_milk_dairy';
        $db2Pass = getenv('DB2_PASS') ?: 'AC]WO/mL9';
        $db2Name = getenv('DB2_NAME') ?: 'u367009900_milk_dairy';

        // Connect primary DB
        $this->db = new mysqli($db1Host, $db1User, $db1Pass, $db1Name);
        if ($this->db->connect_error) {
            die('Database connection failed: ' . $this->db->connect_error);
        }
        $this->db->set_charset('utf8mb4');

        // Connect secondary (milk_dairy) DB
        $this->db2 = new mysqli($db2Host, $db2User, $db2Pass, $db2Name);
        if ($this->db2->connect_error) {
            die('Database milk_dairy connection failed: ' . $this->db2->connect_error);
        }
        $this->db2->set_charset('utf8mb4');
    }
    public function getConnection()
    {
        return $this->db;
    }
    public function getConnection2()
    {
        return $this->db2;
    }
}