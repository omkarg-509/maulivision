<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list.php");
    exit;
}

require_csrf_token();

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: list.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
$stmt->bind_param("i", $id);

$stmt->execute();
$stmt->close();

header("Location: list.php");
exit;
