<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order_add.php');
    exit;
}

require_csrf_token();

// ======================
// Main Inputs
// ======================
$customer_id = (int)($_POST['customer_id'] ?? 0);
$order_date  = trim($_POST['order_date'] ?? '');
$advance     = (float)($_POST['advance'] ?? 0);
$notes       = trim($_POST['notes'] ?? '');

// ======================
// Items Arrays
// ======================
$door_types    = $_POST['door_type'] ?? [];
$width_heights = $_POST['width_height'] ?? [];
$qtys          = $_POST['qty'] ?? [];
$rates         = $_POST['rate'] ?? [];

// ======================
// Validation
// ======================
if ($customer_id <= 0) {
    header('Location: order_add.php?error=' . urlencode('Please select customer.'));
    exit;
}

if ($order_date === '') {
    header('Location: order_add.php?error=' . urlencode('Please select order date.'));
    exit;
}

if (!is_array($door_types) || count($door_types) === 0) {
    header('Location: order_add.php?error=' . urlencode('Please add at least 1 item.'));
    exit;
}

if ($advance < 0) {
    $advance = 0;
}

// ======================
// Clean & Build Items
// ======================
$items = [];
$grand_total = 0;

for ($i = 0; $i < count($door_types); $i++) {
    $door_type = trim($door_types[$i] ?? '');
    $wh        = trim($width_heights[$i] ?? '');

    $qty  = (int)($qtys[$i] ?? 0);
    $rate = (float)($rates[$i] ?? 0);

    // skip empty row
    if ($door_type === '' && $wh === '' && $qty <= 0 && $rate <= 0) {
        continue;
    }

    if ($qty <= 0) {
        header('Location: order_add.php?error=' . urlencode('Quantity must be at least 1 (Row ' . ($i + 1) . ')'));
        exit;
    }

    if ($rate < 0) {
        header('Location: order_add.php?error=' . urlencode('Rate must be valid (Row ' . ($i + 1) . ')'));
        exit;
    }

    $amount = $qty * $rate;
    $grand_total += $amount;

    $items[] = [
        'door_type' => $door_type,
        'wh' => $wh,
        'qty' => $qty,
        'rate' => $rate,
        'amount' => $amount,
    ];
}

if (count($items) === 0) {
    header('Location: order_add.php?error=' . urlencode('Please add at least 1 valid item.'));
    exit;
}

// ======================
// Totals + Status
// ======================
$balance = $grand_total - $advance;
if ($balance < 0) {
    $balance = 0;
}

$status = ($balance <= 0) ? 'paid' : 'pending';

// ======================
// Insert Order + Items
// ======================
$conn->begin_transaction();

try {
    // 1) Insert into orders
    $stmt = $conn->prepare('
        INSERT INTO orders (customer_id, order_date, total_amount, advance, balance, status, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ');

    $stmt->bind_param(
        'isdddss',
        $customer_id,
        $order_date,
        $grand_total,
        $advance,
        $balance,
        $status,
        $notes
    );

    if (!$stmt->execute()) {
        throw new Exception('Order insert failed');
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

    // 2) Insert items
    $stmt2 = $conn->prepare('
        INSERT INTO order_items (order_id, item_name, door_type, width_height, qty, rate, amount)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ');

    foreach ($items as $it) {
        $item_name = 'Door';

        $stmt2->bind_param(
            'isssidd',
            $order_id,
            $item_name,
            $it['door_type'],
            $it['wh'],
            $it['qty'],
            $it['rate'],
            $it['amount']
        );

        if (!$stmt2->execute()) {
            throw new Exception('Item insert failed');
        }
    }

    $stmt2->close();

    $conn->commit();

    header('Location: order_add.php?success=1');
    exit;

} catch (Exception $e) {
    $conn->rollback();
    header('Location: order_add.php?error=' . urlencode('Database error. Try again.'));
    exit;
}
