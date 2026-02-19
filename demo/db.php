<?php
// app/config/db.php

// Database Config
$DB_HOST = "localhost";   // ✅ correct (hosting मध्ये बहुतेक localhost असतो)
$DB_USER = "u367009900_doorapp";
$DB_PASS = "&u9CPI1(dq1mm;JdaQeH";
$DB_NAME = "u367009900_doorapp";

// Connection (mysqli)
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check Connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>
