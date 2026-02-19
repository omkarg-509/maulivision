<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add.php");
    exit;
}

require_csrf_token();

$name = trim($_POST['name'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($name === '' || $mobile === '') {
    header("Location: add.php?error=" . urlencode("Name and mobile are required."));
    exit;
}

// Mobile validation (basic)
if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    header("Location: add.php?error=" . urlencode("Enter valid 10 digit mobile number."));
    exit;
}

$stmt = $conn->prepare("INSERT INTO customers (name, mobile, address) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $mobile, $address);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: add.php?success=1");
    exit;
} else {
    $stmt->close();
    header("Location: add.php?error=" . urlencode("Database error. Try again."));
    exit;
}
