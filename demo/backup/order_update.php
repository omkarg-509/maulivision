<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: order_list.php");
    exit;
}

$id          = (int)($_POST['id'] ?? 0);
$item_id     = (int)($_POST['item_id'] ?? 0);
$customer_id = (int)($_POST['customer_id'] ?? 0);

$order_date  = trim($_POST['order_date'] ?? '');
$work_status = trim($_POST['work_status'] ?? 'new');

$advance     = (float)($_POST['advance'] ?? 0);
$notes       = trim($_POST['notes'] ?? '');

$door_type   = trim($_POST['door_type'] ?? '');
$brand       = trim($_POST['brand'] ?? '');
$width       = trim($_POST['width'] ?? '');
$height      = trim($_POST['height'] ?? '');
$qty         = (int)($_POST['qty'] ?? 1);
$rate        = (float)($_POST['rate'] ?? 0);

if ($id <= 0 || $customer_id <= 0 || $order_date === '') {
    header("Location: order_edit.php?id=".$id."&error=" . urlencode("Invalid data."));
    exit;
}

if ($qty <= 0) $qty = 1;
if ($rate < 0) $rate = 0;
if ($advance < 0) $advance = 0;

// Work status validation
$allowed = ['new','in_progress','ready','delivered'];
if (!in_array($work_status, $allowed)) {
    $work_status = 'new';
}

// Calculate totals
$total_amount = $qty * $rate;
$balance = $total_amount - $advance;
if ($balance < 0) $balance = 0;

// Payment status auto
$status = ($balance <= 0) ? 'paid' : 'pending';

// Check order exists
$stmt0 = $conn->prepare("SELECT id FROM orders WHERE id=? LIMIT 1");
$stmt0->bind_param("i", $id);
$stmt0->execute();
$res0 = $stmt0->get_result();
if (!$res0 || $res0->num_rows !== 1) {
    $stmt0->close();
    header("Location: order_list.php");
    exit;
}
$stmt0->close();

// Update order
$stmt = $conn->prepare("
    UPDATE orders 
    SET customer_id=?, order_date=?, total_amount=?, advance=?, balance=?, status=?, work_status=?, notes=?
    WHERE id=?
");
$stmt->bind_param(
    "isdddsssi",
    $customer_id,
    $order_date,
    $total_amount,
    $advance,
    $balance,
    $status,
    $work_status,
    $notes,
    $id
);

if (!$stmt->execute()) {
    $stmt->close();
    header("Location: order_edit.php?id=".$id."&error=" . urlencode("Order update failed."));
    exit;
}
$stmt->close();

// Update item (if exists)
if ($item_id > 0) {

    $amount = $qty * $rate;

    $stmt2 = $conn->prepare("
        UPDATE order_items 
        SET item_name=?, door_type=?, brand=?, width=?, height=?, qty=?, rate=?, amount=?
        WHERE id=? AND order_id=?
    ");

    $item_name = "Door Item";

    $stmt2->bind_param(
        "sssssidddi",
        $item_name,
        $door_type,
        $brand,
        $width,
        $height,
        $qty,
        $rate,
        $amount,
        $item_id,
        $id
    );

    $stmt2->execute();
    $stmt2->close();

} else {
    // If item missing, insert new
    $amount = $qty * $rate;

    $stmt3 = $conn->prepare("
        INSERT INTO order_items (order_id, item_name, door_type, brand, width, height, qty, rate, amount)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $item_name = "Door Item";

    $stmt3->bind_param(
        "isssssid d",
        $id,
        $item_name,
        $door_type,
        $brand,
        $width,
        $height,
        $qty,
        $rate,
        $amount
    );

    // NOTE: Some servers may error due to type string.
    // We'll do a safe insert with correct types below.
    $stmt3->close();

    $stmt3 = $conn->prepare("
        INSERT INTO order_items (order_id, item_name, door_type, brand, width, height, qty, rate, amount)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt3->bind_param(
        "isssssidd",
        $id,
        $item_name,
        $door_type,
        $brand,
        $width,
        $height,
        $qty,
        $rate,
        $amount
    );
    $stmt3->execute();
    $stmt3->close();
}

header("Location: order_view.php?id=".$id);
exit;
