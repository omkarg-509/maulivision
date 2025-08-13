<?php
try {
    $conn = new PDO(
        'mysql:host=localhost;dbname=u367009900_maulivision;charset=utf8mb4',
        'u367009900_maulivision',
        'S54/t&XCIup+'
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}