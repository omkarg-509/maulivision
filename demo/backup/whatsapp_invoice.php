<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: invoices_list.php");
    exit;
}

// Fetch invoice + order + customer
$stmt = $conn->prepare("
    SELECT 
        i.id as invoice_id,
        i.invoice_no,
        i.invoice_date,

        o.id as order_id,
        o.total_amount,
        o.advance,
        o.balance,

        c.name as customer_name,
        c.mobile as customer_mobile

    FROM invoices i
    INNER JOIN orders o ON o.id = i.order_id
    INNER JOIN customers c ON c.id = o.customer_id
    WHERE i.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows !== 1) {
    $stmt->close();
    header("Location: invoices_list.php");
    exit;
}

$data = $res->fetch_assoc();
$stmt->close();

// Mobile clean (only digits)
$mobile = preg_replace('/[^0-9]/', '', $data['customer_mobile']);

// If mobile is 10 digits, add India code 91
if (strlen($mobile) === 10) {
    $mobile = "91" . $mobile;
}

// Invoice view link
// NOTE: If your website has domain, set it here for better link.
// $invoiceLink = "Invoice Link: " . "http://" . $_SERVER['HTTP_HOST'] . "/public/invoice_view.php?id=" . (int)$data['invoice_id'];

// Message
$message = "Hello " . $data['customer_name'] . ",%0A";
$message .= "Your Door Bill is ready.%0A%0A";
$message .= "Invoice No: " . $data['invoice_no'] . "%0A";
$message .= "Invoice Date: " . $data['invoice_date'] . "%0A";
$message .= "Total: ₹" . number_format((float)$data['total_amount'], 2) . "%0A";
$message .= "Advance: ₹" . number_format((float)$data['advance'], 2) . "%0A";
$message .= "Balance: ₹" . number_format((float)$data['balance'], 2) . "%0A%0A";
$message .= $invoiceLink . "%0A%0A";
$message .= "Thank you!";

// WhatsApp link
$waUrl = "https://wa.me/" . $mobile . "?text=" . $message;

// Redirect to WhatsApp
header("Location: " . $waUrl);
exit;
