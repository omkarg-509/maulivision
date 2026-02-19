<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

// Fetch customers for dropdown
$customers = [];
$q = $conn->query("SELECT id, name, mobile FROM customers ORDER BY id DESC");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $customers[] = $row;
    }
}

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Order - Door Maker App</title>

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
            font-weight: 800;
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
            font-weight: 800;
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
            font-weight: 700;
        }
        .msg-error {
            background: #ffe5e5;
            color: #b10000;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 700;
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
            .items-header {
                display: none;
            }

            .item-row {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 800px) {
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Add Order</div>
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <a href="order_list.php">Order List</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">New Order</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="order_list.php">← Back</a>
            </div>
        </div>

        <div class="card">

            <?php if ($success === "1") { ?>
                <div class="msg-success">Order created successfully!</div>
            <?php } ?>

            <?php if ($error !== "") { ?>
                <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>

            <form method="POST" action="order_save.php" autocomplete="off">

                <div class="section-title">Customer Details</div>

                <label>Select Customer *</label>
                <select name="customer_id" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customers as $c) { ?>
                        <option value="<?php echo (int)$c['id']; ?>">
                            <?php echo htmlspecialchars($c['name']); ?> (<?php echo htmlspecialchars($c['mobile']); ?>)
                        </option>
                    <?php } ?>
                </select>

                <div class="row">
                    <div>
                        <label>Order Date *</label>
                        <input type="date" name="order_date" value="<?php echo date('Y-m-d'); ?>" required />
                    </div>

                    <!-- ADVANCE ONLY ONE INPUT -->
                    <div>
                        <label>Advance (₹)</label>
                        <input type="number" name="advance" id="advance" value="0" min="0" step="0.01" />
                    </div>
                </div>

                <label>Notes (optional)</label>
                <textarea name="notes" placeholder="Example: FRP Supreme - Morning"></textarea>

                <div class="section-title">Door Items</div>

                <div class="items-header">
                    <div>Door Type</div>
                    <div>Width/Height</div>
                    <div>Qty</div>
                    <div>Rate (₹)</div>
                    <div>Total (₹)</div>
                    <div>Action</div>
                </div>

                <div id="itemsContainer"></div>

                <button type="button" class="btn btn-dark" id="addItemBtn" style="margin-top:10px;">
                    + Add Item
                </button>

                <!-- GRAND TOTAL ONLY -->
                <div class="row" style="margin-top:14px;">
                    <div>
                        <label>Grand Total (₹)</label>
                        <input type="number" name="total_amount" id="total_amount" value="0" step="0.01" readonly />
                    </div>

                    <div></div>
                </div>

                <div class="total-box">
                    <div class="total-row">
                        <div>Balance (₹)</div>
                        <div id="balanceText">₹0.00</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:14px; font-size:16px; font-weight:900; margin-top:14px;">
                    Save Order
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

    function addItemRow(defaults = {}) {
        const row = document.createElement("div");
        row.className = "item-row";

        row.innerHTML = `
            <input type="text" name="door_type[]" placeholder="FRP / Flush / Plywood..." value="${defaults.door_type || ""}" />
            <input type="text" name="width_height[]" placeholder="3x7 / 3 ft x 7 ft" value="${defaults.width_height || ""}" />

            <input type="number" name="qty[]" class="qty" value="${defaults.qty || 1}" min="1" required />
            <input type="number" name="rate[]" class="rate" value="${defaults.rate || 0}" min="0" step="0.01" required />

            <div class="item-total">₹0.00</div>

            <button type="button" class="remove-btn">Remove</button>
        `;

        itemsContainer.appendChild(row);

        const qtyInput = row.querySelector(".qty");
        const rateInput = row.querySelector(".rate");
        const removeBtn = row.querySelector(".remove-btn");

        qtyInput.addEventListener("input", calcAllTotals);
        rateInput.addEventListener("input", calcAllTotals);

        removeBtn.addEventListener("click", () => {
            row.remove();
            calcAllTotals();
        });

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

    addItemBtn.addEventListener("click", () => addItemRow());
    advanceInput.addEventListener("input", calcAllTotals);

    // default 1 item
    addItemRow();
</script>

</body>
</html>
