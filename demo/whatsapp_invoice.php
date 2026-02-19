<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: invoices_list.php"); exit; }

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
        c.mobile as customer_mobile,
        c.address as customer_address

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

// Fetch items
$items = [];
$stmt2 = $conn->prepare("SELECT * FROM order_items WHERE order_id=? ORDER BY id ASC");
$stmt2->bind_param("i", $data['order_id']);
$stmt2->execute();
$res2 = $stmt2->get_result();
if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        $items[] = $row;
    }
}
$stmt2->close();

// Mobile clean
$mobile = preg_replace('/[^0-9]/', '', $data['customer_mobile']);
if (strlen($mobile) === 10) $mobile = "91" . $mobile;

// Company Name
$companyName = "MAULI DOOR MAKER";

// ======================
// Build WhatsApp Message
// ======================
$message = "Hello " . $data['customer_name'] . ",\n";
$message .= "Your invoice is ready.\n\n";

$message .= "*" . $companyName . "*\n";
$message .= "------------------------\n";

$message .= "*Invoice No:* " . $data['invoice_no'] . "\n";
$message .= "*Invoice Date:* " . $data['invoice_date'] . "\n";
$message .= "*Order ID:* #" . $data['order_id'] . "\n\n";

$message .= "*Customer Details*\n";
$message .= "Name: " . $data['customer_name'] . "\n";
$message .= "Mobile: " . $data['customer_mobile'] . "\n";

if (!empty($data['customer_address'])) {
    $message .= "Address: " . $data['customer_address'] . "\n";
}

$message .= "\n*Items*\n";
$message .= "------------------------\n";

if (count($items) > 0) {

    $sr = 1;
    foreach ($items as $it) {

        $doorType = trim($it['door_type'] ?? '');
        $size = trim($it['width_height'] ?? '');
        $qty = (int)($it['qty'] ?? 0);
        $rate = (float)($it['rate'] ?? 0);
        $amount = (float)($it['amount'] ?? 0);

        $message .= $sr . ") " . $doorType . "\n";
        if ($size !== "") $message .= "   Size: " . $size . "\n";
        $message .= "   Qty: " . $qty . " | Rate: ₹" . number_format($rate, 2) . "\n";
        $message .= "   Amount: ₹" . number_format($amount, 2) . "\n\n";

        $sr++;
    }

} else {
    $message .= "No items found.\n\n";
}

// Payment Summary
$message .= "------------------------\n";
$message .= "*Payment Summary*\n";
$message .= "Total: ₹" . number_format((float)$data['total_amount'], 2) . "\n";
$message .= "Advance: ₹" . number_format((float)$data['advance'], 2) . "\n";
$message .= "Balance: ₹" . number_format((float)$data['balance'], 2) . "\n";
$message .= "------------------------\n\n";

$message .= "Thank you!\n";
$message .= $companyName;

// WhatsApp link
$waUrl = "https://wa.me/" . $mobile . "?text=" . urlencode($message);

header("Location: " . $waUrl);
exit;
