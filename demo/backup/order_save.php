<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: order_add.php");
    exit;
}

$customer_id = (int)($_POST['customer_id'] ?? 0);
$order_date  = trim($_POST['order_date'] ?? '');
$advance     = (float)($_POST['advance'] ?? 0);
$notes       = trim($_POST['notes'] ?? '');

// Item
$door_type = trim($_POST['door_type'] ?? '');
$brand     = trim($_POST['brand'] ?? '');
$width     = trim($_POST['width'] ?? '');
$height    = trim($_POST['height'] ?? '');
$qty       = (int)($_POST['qty'] ?? 0);
$rate      = (float)($_POST['rate'] ?? 0);

// Basic validation
if ($customer_id <= 0) {
    header("Location: order_add.php?error=" . urlencode("Please select customer."));
    exit;
}

if ($order_date === '') {
    header("Location: order_add.php?error=" . urlencode("Please select order date."));
    exit;
}

if ($qty <= 0) {
    header("Location: order_add.php?error=" . urlencode("Quantity must be at least 1."));
    exit;
}

if ($rate < 0) {
    header("Location: order_add.php?error=" . urlencode("Rate must be valid."));
    exit;
}

$total_amount = $qty * $rate;
$balance = $total_amount - $advance;
if ($balance < 0) $balance = 0;

$status = ($balance <= 0) ? "paid" : "pending";

$conn->begin_transaction();

try {

    // Insert order
    $stmt = $conn->prepare("
        INSERT INTO orders (customer_id, order_date, total_amount, advance, balance, status, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isdddss", $customer_id, $order_date, $total_amount, $advance, $balance, $status, $notes);

    if (!$stmt->execute()) {
        throw new Exception("Order insert failed");
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert order item (1 item for demo)
    $amount = $total_amount;

    $stmt2 = $conn->prepare("
        INSERT INTO order_items (order_id, item_name, door_type, brand, width, height, qty, rate, amount)
        VALUES (?, 'Door', ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt2->bind_param("issssidd", $order_id, $door_type, $brand, $width, $height, $qty, $rate, $amount);

    if (!$stmt2->execute()) {
        throw new Exception("Order item insert failed");
    }

    $stmt2->close();

    $conn->commit();

    header("Location: order_add.php?success=1");
    exit;

} catch (Exception $e) {

    $conn->rollback();

    header("Location: order_add.php?error=" . urlencode("Database error. Try again."));
    exit;
}
