<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: order_list.php");
    exit;
}

// Fetch customers
$customers = [];
$q = $conn->query("SELECT id, name, mobile FROM customers ORDER BY id DESC");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $customers[] = $row;
    }
}

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows !== 1) {
    $stmt->close();
    header("Location: order_list.php");
    exit;
}
$order = $res->fetch_assoc();
$stmt->close();

// Fetch items (ALL)
$items = [];
$stmt2 = $conn->prepare("SELECT * FROM order_items WHERE order_id=? ORDER BY id ASC");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$res2 = $stmt2->get_result();

if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        $items[] = $row;
    }
}
$stmt2->close();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

$work_status = $order['work_status'] ?? 'new';

// If no items, keep 1 empty item
if (count($items) === 0) {
    $items[] = [
        "id" => 0,
        "door_type" => "",
        "width_height" => "",
        "qty" => 1,
        "rate" => 0,
        "amount" => 0
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Order - Door Maker App</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f6fb; }

        .topbar {
            background: #111827;
            color: #fff;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .topbar .title {
            font-size: 18px;
            font-weight: 700;
        }
        .topbar a {
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .container {
            padding: 18px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 18px;
            font-weight: 900;
            color: #111;
        }

        .btn {
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 14px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-dark { background: #111827; color: #fff; }
        .btn-danger { background: #ef4444; color: #fff; }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #333;
            font-weight: 900;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 14px;
            margin-bottom: 14px;
            background: #fff;
        }
        textarea { min-height: 80px; resize: vertical; }

        .msg-success {
            background: #dcfce7;
            color: #166534;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 800;
        }
        .msg-error {
            background: #ffe5e5;
            color: #b10000;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 800;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 900;
            color: #111;
            margin: 8px 0 10px;
        }

        .total-box {
            background: #f9fafb;
            border: 1px solid #eee;
            padding: 14px;
            border-radius: 14px;
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: 900;
            font-size: 16px;
            color: #111;
        }

        /* ITEMS GRID */
        .items-header, .item-row {
            display: grid;
            grid-template-columns: 2fr 1.2fr 0.7fr 1fr 1fr 0.8fr;
            gap: 12px;
            align-items: center;
        }

        .items-header {
            background: #f3f4f6;
            padding: 10px 12px;
            border-radius: 12px;
            font-weight: 900;
            font-size: 13px;
            color: #111;
            margin-bottom: 10px;
        }

        .item-row {
            background: #fff;
            padding: 10px 12px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .item-row input {
            margin-bottom: 0;
        }

        .item-total {
            font-weight: 900;
            color: #111;
        }

        .remove-btn {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 10px 12px;
            border-radius: 10px;
            font-weight: 900;
            cursor: pointer;
        }

        .remove-btn:hover {
            opacity: 0.95;
        }

        @media(max-width: 900px) {
            .items-header { display: none; }
            .item-row { grid-template-columns: 1fr; }
        }

        @media(max-width: 800px) {
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Edit Order</div>
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <a href="order_list.php">Order List</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Edit Order #<?php echo (int)$order['id']; ?></div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="order_view.php?id=<?php echo (int)$order['id']; ?>">← Back</a>
                <a class="btn btn-danger"
                   href="order_delete.php?id=<?php echo (int)$order['id']; ?>"
                   onclick="return confirm('Delete this order?');">
                   Delete
                </a>
            </div>
        </div>

        <div class="card">

            <?php if ($success === "1") { ?>
                <div class="msg-success">Order updated successfully!</div>
            <?php } ?>

            <?php if ($error !== "") { ?>
                <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>

            <form method="POST" action="order_update.php" autocomplete="off">

                <input type="hidden" name="id" value="<?php echo (int)$order['id']; ?>" />

                <div class="section-title">Customer Details</div>

                <label>Select Customer *</label>
                <select name="customer_id" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customers as $c) { ?>
                        <option value="<?php echo (int)$c['id']; ?>"
                            <?php echo ((int)$order['customer_id'] === (int)$c['id']) ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($c['name']); ?> (<?php echo htmlspecialchars($c['mobile']); ?>)
                        </option>
                    <?php } ?>
                </select>

                <div class="row">
                    <div>
                        <label>Order Date *</label>
                        <input type="date" name="order_date"
                               value="<?php echo htmlspecialchars($order['order_date']); ?>" required />
                    </div>

                    <div>
                        <label>Work Status *</label>
                        <select name="work_status" required>
                            <option value="new" <?php echo ($work_status==='new')?'selected':''; ?>>NEW</option>
                            <option value="in_progress" <?php echo ($work_status==='in_progress')?'selected':''; ?>>IN PROGRESS</option>
                            <option value="ready" <?php echo ($work_status==='ready')?'selected':''; ?>>READY</option>
                            <option value="delivered" <?php echo ($work_status==='delivered')?'selected':''; ?>>DELIVERED</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>Advance (₹)</label>
                        <input type="number" name="advance" id="advance"
                               value="<?php echo htmlspecialchars($order['advance']); ?>" min="0" step="0.01" />
                    </div>

                    <!--<div>-->
                    <!--    <label>Payment Status</label>-->
                    <!--    <input type="text" value="AUTO (Paid/Pending)" readonly />-->
                    <!--</div>-->
                    <div>
    <label>Payment Status *</label>
    <select name="status" required>
        <option value="pending" <?php echo ($order['status']==='pending')?'selected':''; ?>>PENDING (UNPAID)</option>
        <option value="paid" <?php echo ($order['status']==='paid')?'selected':''; ?>>PAID</option>
    </select>
</div>

                </div>

                <label>Notes (optional)</label>
                <textarea name="notes"><?php echo htmlspecialchars($order['notes'] ?? ''); ?></textarea>

                <div class="section-title">Door Items</div>

                <div class="items-header">
                    <div>Door Type</div>
                    <div>Width/Height</div>
                    <div>Qty</div>
                    <div>Rate (₹)</div>
                    <div>Total (₹)</div>
                    <div>Action</div>
                </div>

                <div id="itemsContainer">
                    <?php foreach ($items as $it) { ?>
                        <div class="item-row">
                            <input type="hidden" name="item_id[]" value="<?php echo (int)$it['id']; ?>" />

                            <input type="text" name="door_type[]" placeholder="FRP / Flush / Plywood..."
                                   value="<?php echo htmlspecialchars($it['door_type'] ?? ''); ?>" />

                            <input type="text" name="width_height[]" placeholder="3x7 / 3 ft x 7 ft"
                                   value="<?php echo htmlspecialchars($it['width_height'] ?? ''); ?>" />

                            <input type="number" name="qty[]" class="qty"
                                   value="<?php echo (int)($it['qty'] ?? 1); ?>" min="1" required />

                            <input type="number" name="rate[]" class="rate"
                                   value="<?php echo htmlspecialchars($it['rate'] ?? 0); ?>" min="0" step="0.01" required />

                            <div class="item-total">₹0.00</div>

                            <button type="button" class="remove-btn">Remove</button>
                        </div>
                    <?php } ?>
                </div>

                <button type="button" class="btn btn-dark" id="addItemBtn" style="margin-top:10px;">
                    + Add Item
                </button>

                <div class="row" style="margin-top:14px;">
                    <div>
                        <label>Grand Total (₹)</label>
                        <input type="number" name="total_amount" id="total_amount"
                               value="<?php echo htmlspecialchars($order['total_amount']); ?>" step="0.01" readonly />
                    </div>
                    <div></div>
                </div>

                <div class="total-box">
                    <div class="total-row">
                        <div>Balance (₹)</div>
                        <div id="balanceText">₹0.00</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"
                        style="width:100%; padding:14px; font-size:16px; font-weight:900; margin-top:14px;">
                    Update Order
                </button>

            </form>

        </div>

    </div>

<script>
    const itemsContainer = document.getElementById("itemsContainer");
    const addItemBtn = document.getElementById("addItemBtn");

    const totalAmountInput = document.getElementById("total_amount");
    const advanceInput = document.getElementById("advance");
    const balanceText = document.getElementById("balanceText");

    function bindRowEvents(row) {
        const qtyInput = row.querySelector(".qty");
        const rateInput = row.querySelector(".rate");
        const removeBtn = row.querySelector(".remove-btn");

        qtyInput.addEventListener("input", calcAllTotals);
        rateInput.addEventListener("input", calcAllTotals);

        removeBtn.addEventListener("click", () => {
            // If it is existing item, mark it deleted
            const hiddenId = row.querySelector('input[name="item_id[]"]');
            if (hiddenId && hiddenId.value != "0") {
                // Create hidden deleted array
                const del = document.createElement("input");
                del.type = "hidden";
                del.name = "deleted_item_id[]";
                del.value = hiddenId.value;
                document.querySelector("form").appendChild(del);
            }

            row.remove();
            calcAllTotals();
        });
    }

    function addItemRow() {
        const row = document.createElement("div");
        row.className = "item-row";

        row.innerHTML = `
            <input type="hidden" name="item_id[]" value="0" />

            <input type="text" name="door_type[]" placeholder="FRP / Flush / Plywood..." value="" />
            <input type="text" name="width_height[]" placeholder="3x7 / 3 ft x 7 ft" value="" />

            <input type="number" name="qty[]" class="qty" value="1" min="1" required />
            <input type="number" name="rate[]" class="rate" value="0" min="0" step="0.01" required />

            <div class="item-total">₹0.00</div>

            <button type="button" class="remove-btn">Remove</button>
        `;

        itemsContainer.appendChild(row);
        bindRowEvents(row);
        calcAllTotals();
    }

    function calcAllTotals() {
        let grandTotal = 0;
        const rows = document.querySelectorAll(".item-row");

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector(".qty").value || 0);
            const rate = parseFloat(row.querySelector(".rate").value || 0);

            const itemTotal = qty * rate;
            grandTotal += itemTotal;

            row.querySelector(".item-total").innerText = "₹" + itemTotal.toFixed(2);
        });

        totalAmountInput.value = grandTotal.toFixed(2);

        let adv = parseFloat(advanceInput.value || 0);
        let bal = grandTotal - adv;
        if (bal < 0) bal = 0;

        balanceText.innerText = "₹" + bal.toFixed(2);
    }

    // Bind existing rows
    document.querySelectorAll(".item-row").forEach(row => bindRowEvents(row));

    addItemBtn.addEventListener("click", addItemRow);
    advanceInput.addEventListener("input", calcAllTotals);

    calcAllTotals();
</script>

</body>
</html>
