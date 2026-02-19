<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list.php");
    exit;
}

require_csrf_token();

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($id <= 0) {
    header("Location: list.php");
    exit;
}

if ($name === '' || $mobile === '') {
    header("Location: edit.php?id=".$id."&error=" . urlencode("Name and mobile are required."));
    exit;
}

// Mobile validation (basic)
if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    header("Location: edit.php?id=".$id."&error=" . urlencode("Enter valid 10 digit mobile number."));
    exit;
}

$stmt = $conn->prepare("UPDATE customers SET name=?, mobile=?, address=? WHERE id=?");
$stmt->bind_param("sssi", $name, $mobile, $address, $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: edit.php?id=".$id."&success=1");
    exit;
} else {
    $stmt->close();
    header("Location: edit.php?id=".$id."&error=" . urlencode("Database error. Try again."));
    exit;
}
