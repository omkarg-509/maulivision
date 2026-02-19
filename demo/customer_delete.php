<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: list.php");
    exit;
}

// Delete customer
$stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
$stmt->bind_param("i", $id);

$stmt->execute();
$stmt->close();

header("Location: list.php");
exit;
