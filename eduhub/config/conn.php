

<?php
// conn.php

$host = "localhost";
$user = "u367009900_sciedin";
$pass = "&u9CPI1(dq1mm;JdaQeH";
$db   = "u367009900_sciedin";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
