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

// ======================
// Main Inputs
// ======================
$id          = (int)($_POST['id'] ?? 0);
$customer_id = (int)($_POST['customer_id'] ?? 0);

$order_date  = trim($_POST['order_date'] ?? '');
$work_status = trim($_POST['work_status'] ?? 'new');

$advance     = (float)($_POST['advance'] ?? 0);
$notes       = trim($_POST['notes'] ?? '');

// Manual Payment Status
$status = trim($_POST['status'] ?? 'pending');
if ($status !== 'paid' && $status !== 'pending') {
    $status = 'pending';
}

// Items arrays
$item_ids      = $_POST['item_id'] ?? [];
$door_types    = $_POST['door_type'] ?? [];
$width_heights = $_POST['width_height'] ?? [];
$qtys          = $_POST['qty'] ?? [];
$rates         = $_POST['rate'] ?? [];

// Deleted items
$deleted_item_ids = $_POST['deleted_item_id'] ?? [];

// ======================
// Validation
// ======================
if ($id <= 0 || $customer_id <= 0 || $order_date === '') {
    header("Location: order_edit.php?id=".$id."&error=" . urlencode("Invalid data."));
    exit;
}

if ($advance < 0) $advance = 0;

// Work status validation
$allowed = ['new','in_progress','ready','delivered'];
if (!in_array($work_status, $allowed)) {
    $work_status = 'new';
}

if (!is_array($door_types) || count($door_types) === 0) {
    header("Location: order_edit.php?id=".$id."&error=" . urlencode("Please add at least 1 item."));
    exit;
}

// ======================
// Check order exists
// ======================
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

// ======================
// Build Items + Totals
// ======================
$items = [];
$grand_total = 0;

for ($i = 0; $i < count($door_types); $i++) {

    $item_id = (int)($item_ids[$i] ?? 0);

    $door_type = trim($door_types[$i] ?? '');
    $wh        = trim($width_heights[$i] ?? '');

    $qty  = (int)($qtys[$i] ?? 0);
    $rate = (float)($rates[$i] ?? 0);

    // skip empty row
    if ($door_type === '' && $wh === '' && $qty <= 0 && $rate <= 0) {
        continue;
    }

    if ($qty <= 0) {
        header("Location: order_edit.php?id=".$id."&error=" . urlencode("Quantity must be at least 1 (Row " . ($i + 1) . ")"));
        exit;
    }

    if ($rate < 0) {
        header("Location: order_edit.php?id=".$id."&error=" . urlencode("Rate must be valid (Row " . ($i + 1) . ")"));
        exit;
    }

    $amount = $qty * $rate;
    $grand_total += $amount;

    $items[] = [
        "id" => $item_id,
        "door_type" => $door_type,
        "wh" => $wh,
        "qty" => $qty,
        "rate" => $rate,
        "amount" => $amount
    ];
}

if (count($items) === 0) {
    header("Location: order_edit.php?id=".$id."&error=" . urlencode("Please add at least 1 valid item."));
    exit;
}

// ======================
// Totals
// ======================
$balance = $grand_total - $advance;
if ($balance < 0) $balance = 0;

// Manual Payment Logic
if ($status === "paid") {
    $balance = 0;
}

// ======================
// DB Transaction
// ======================
$conn->begin_transaction();

try {

    // 1) Update order
    $stmt = $conn->prepare("
        UPDATE orders
        SET customer_id=?, order_date=?, total_amount=?, advance=?, balance=?, status=?, work_status=?, notes=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "isdddsssi",
        $customer_id,
        $order_date,
        $grand_total,
        $advance,
        $balance,
        $status,
        $work_status,
        $notes,
        $id
    );

    if (!$stmt->execute()) {
        throw new Exception("Order update failed");
    }
    $stmt->close();

    // 2) Delete removed items
    if (is_array($deleted_item_ids) && count($deleted_item_ids) > 0) {

        $stmtDel = $conn->prepare("DELETE FROM order_items WHERE id=? AND order_id=?");

        foreach ($deleted_item_ids as $delId) {
            $delId = (int)$delId;
            if ($delId > 0) {
                $stmtDel->bind_param("ii", $delId, $id);
                if (!$stmtDel->execute()) {
                    throw new Exception("Item delete failed");
                }
            }
        }

        $stmtDel->close();
    }

    // 3) Update existing items + Insert new items
    $stmtUpdate = $conn->prepare("
        UPDATE order_items
        SET door_type=?, width_height=?, qty=?, rate=?, amount=?
        WHERE id=? AND order_id=?
    ");

    $stmtInsert = $conn->prepare("
        INSERT INTO order_items (order_id, item_name, door_type, width_height, qty, rate, amount)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($items as $it) {

        // Existing item
        if ($it['id'] > 0) {

            $stmtUpdate->bind_param(
                "ssiddii",
                $it['door_type'],
                $it['wh'],
                $it['qty'],
                $it['rate'],
                $it['amount'],
                $it['id'],
                $id
            );

            if (!$stmtUpdate->execute()) {
                throw new Exception("Item update failed");
            }

        } else {
            // New item
            $item_name = "Door";

            $stmtInsert->bind_param(
                "isssidd",
                $id,
                $item_name,
                $it['door_type'],
                $it['wh'],
                $it['qty'],
                $it['rate'],
                $it['amount']
            );

            if (!$stmtInsert->execute()) {
                throw new Exception("Item insert failed");
            }
        }
    }

    $stmtUpdate->close();
    $stmtInsert->close();

    $conn->commit();

    header("Location: order_view.php?id=".$id);
    exit;

} catch (Exception $e) {

    $conn->rollback();

    header("Location: order_edit.php?id=".$id."&error=" . urlencode("Database error. Try again."));
    exit;
}
