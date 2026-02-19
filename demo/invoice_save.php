<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: invoice_create.php");
    exit;
}

require_csrf_token();

$invoice_no   = trim($_POST['invoice_no'] ?? '');
$invoice_date = trim($_POST['invoice_date'] ?? '');
$order_id     = (int)($_POST['order_id'] ?? 0);

if ($invoice_no === '' || $invoice_date === '' || $order_id <= 0) {
    header("Location: invoice_create.php?error=" . urlencode("Please fill all required fields."));
    exit;
}

// Check order exists
$stmt = $conn->prepare("SELECT id FROM orders WHERE id=? LIMIT 1");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows !== 1) {
    $stmt->close();
    header("Location: invoice_create.php?error=" . urlencode("Order not found."));
    exit;
}
$stmt->close();

// Check invoice_no already exists
$stmt2 = $conn->prepare("SELECT id FROM invoices WHERE invoice_no=? LIMIT 1");
$stmt2->bind_param("s", $invoice_no);
$stmt2->execute();
$res2 = $stmt2->get_result();

if ($res2 && $res2->num_rows > 0) {
    $stmt2->close();
    header("Location: invoice_create.php?error=" . urlencode("Invoice number already exists."));
    exit;
}
$stmt2->close();

// Insert invoice
$stmt3 = $conn->prepare("INSERT INTO invoices (order_id, invoice_no, invoice_date) VALUES (?, ?, ?)");
$stmt3->bind_param("iss", $order_id, $invoice_no, $invoice_date);

if ($stmt3->execute()) {
    $stmt3->close();
    header("Location: invoices_list.php");
    exit;
} else {
    $stmt3->close();
    header("Location: invoice_create.php?error=" . urlencode("Database error. Try again."));
    exit;
}
