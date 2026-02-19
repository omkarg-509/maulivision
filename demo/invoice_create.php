<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

// Fetch orders for dropdown (latest first)
$orders = [];
$q = $conn->query("
    SELECT 
        o.id,
        o.order_date,
        o.total_amount,
        o.balance,
        o.status,
        c.name as customer_name,
        c.mobile as customer_mobile
    FROM orders o
    INNER JOIN customers c ON c.id = o.customer_id
    ORDER BY o.id DESC
    LIMIT 200
");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Auto invoice no generator (simple)
$year = date("Y");
$nextNumber = 1;

$q2 = $conn->query("SELECT invoice_no FROM invoices ORDER BY id DESC LIMIT 1");
if ($q2 && $q2->num_rows === 1) {
    $last = $q2->fetch_assoc()['invoice_no'];

    // Example: INV-2026-0005
    $parts = explode("-", $last);
    if (count($parts) === 3) {
        $lastYear = $parts[1];
        $lastNum = (int)$parts[2];

        if ($lastYear == $year) {
            $nextNumber = $lastNum + 1;
        } else {
            $nextNumber = 1;
        }
    }
}

$invoice_no = "INV-" . $year . "-" . str_pad((string)$nextNumber, 4, "0", STR_PAD_LEFT);

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Invoice - Door Maker App</title>

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
        input, select {
            width: 100%;
            padding: 12px 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 14px;
            margin-bottom: 14px;
            background: #fff;
        }

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

        .info {
            background: #f9fafb;
            border: 1px solid #eee;
            padding: 12px;
            border-radius: 12px;
            color: #444;
            line-height: 1.6;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media(max-width: 800px) {
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Create Invoice</div>
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="invoices_list.php">Invoice List</a>
            <a href="order_list.php">Orders</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">New Invoice</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="invoices_list.php">← Back</a>
            </div>
        </div>

        <div class="card">

            <?php if ($success === "1") { ?>
                <div class="msg-success">Invoice created successfully!</div>
            <?php } ?>

            <?php if ($error !== "") { ?>
                <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>

            <div class="info">
                Select an order and generate invoice.  
                पुढे आपण PDF + WhatsApp integration add करू.
            </div>

            <form method="POST" action="invoice_save.php" autocomplete="off">

                <?php echo csrf_field(); ?>

                <div class="row">
                    <div>
                        <label>Invoice No *</label>
                        <input type="text" name="invoice_no"
                               value="<?php echo htmlspecialchars($invoice_no); ?>" readonly />
                    </div>

                    <div>
                        <label>Invoice Date *</label>
                        <input type="date" name="invoice_date"
                               value="<?php echo date('Y-m-d'); ?>" required />
                    </div>
                </div>

                <label>Select Order *</label>
                <select name="order_id" required>
                    <option value="">-- Select Order --</option>
                    <?php foreach ($orders as $o) { ?>
                        <option value="<?php echo (int)$o['id']; ?>">
                            Order #<?php echo (int)$o['id']; ?> |
                            <?php echo htmlspecialchars($o['customer_name']); ?> (<?php echo htmlspecialchars($o['customer_mobile']); ?>) |
                            Total ₹<?php echo number_format((float)$o['total_amount'], 0); ?> |
                            Bal ₹<?php echo number_format((float)$o['balance'], 0); ?> |
                            <?php echo strtoupper($o['status']); ?>
                        </option>
                    <?php } ?>
                </select>

                <button type="submit" class="btn btn-primary"
                        style="width:100%; padding:14px; font-size:16px; font-weight:900;">
                    Create Invoice
                </button>

            </form>

        </div>

    </div>

</body>
</html>
