<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

// Filters
$from = trim($_GET['from'] ?? date('Y-m-01'));
$to   = trim($_GET['to'] ?? date('Y-m-d'));
$status = trim($_GET['status'] ?? 'all'); // all / paid / pending

// Validate dates (basic)
if ($from === '') $from = date('Y-m-01');
if ($to === '') $to = date('Y-m-d');

// Build query
$where = " WHERE DATE(o.order_date) BETWEEN ? AND ? ";
$params = [$from, $to];
$types = "ss";

if ($status === "paid" || $status === "pending") {
    $where .= " AND o.status = ? ";
    $params[] = $status;
    $types .= "s";
}

// Summary
$summary = [
    "total_orders" => 0,
    "total_amount" => 0,
    "total_advance" => 0,
    "total_balance" => 0,
];

// Summary query
$sqlSum = "
    SELECT
        COUNT(*) as total_orders,
        SUM(o.total_amount) as total_amount,
        SUM(o.advance) as total_advance,
        SUM(o.balance) as total_balance
    FROM orders o
    $where
";

$stmtSum = $conn->prepare($sqlSum);
$stmtSum->bind_param($types, ...$params);
$stmtSum->execute();
$resSum = $stmtSum->get_result();
if ($resSum && $resSum->num_rows === 1) {
    $row = $resSum->fetch_assoc();
    $summary["total_orders"] = (int)($row["total_orders"] ?? 0);
    $summary["total_amount"] = (float)($row["total_amount"] ?? 0);
    $summary["total_advance"] = (float)($row["total_advance"] ?? 0);
    $summary["total_balance"] = (float)($row["total_balance"] ?? 0);
}
$stmtSum->close();

// List query
$rows = [];
$sqlList = "
    SELECT
        o.id as order_id,
        o.order_date,
        o.total_amount,
        o.advance,
        o.balance,
        o.status,
        c.name as customer_name,
        c.mobile as customer_mobile
    FROM orders o
    INNER JOIN customers c ON c.id = o.customer_id
    $where
    ORDER BY o.id DESC
";

$stmt = $conn->prepare($sqlList);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

if ($res) {
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Collection Report - Door Maker App</title>

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
            font-weight: 800;
        }
        .topbar a {
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
        }

        .container {
            padding: 18px;
            max-width: 1200px;
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
            padding: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 900;
            margin-bottom: 6px;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 14px;
            background: #fff;
        }

        .filters {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 140px;
            gap: 12px;
            align-items: end;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .box {
            background: #f9fafb;
            border: 1px solid #eee;
            border-radius: 14px;
            padding: 14px;
        }
        .box .label {
            font-size: 13px;
            color: #666;
            font-weight: 900;
            margin-bottom: 8px;
        }
        .box .value {
            font-size: 20px;
            font-weight: 900;
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
        }
        th {
            background: #f9fafb;
            font-weight: 900;
            color: #333;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
        }
        .pending { background: #fff7ed; color: #9a3412; }
        .paid { background: #dcfce7; color: #166534; }

        @media(max-width: 950px) {
            .filters { grid-template-columns: 1fr; }
            .summary { grid-template-columns: 1fr 1fr; }
        }
        @media(max-width: 600px) {
            .summary { grid-template-columns: 1fr; }
        }

        @media print {
            .topbar, .filters, .print-hide { display:none !important; }
            body { background:#fff; }
            .container { padding:0; }
            .card { box-shadow:none; }
        }
        /* Table wrapper for mobile scroll */
.table-wrap{
    width:100%;
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
    border-radius:12px;
}

    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Collection Report</div>
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Collection Report</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="dashboard.php">← Back</a>
                <button class="btn btn-primary print-hide" onclick="window.print()">Print</button>
            </div>
        </div>

        <div class="card print-hide">
            <form method="GET">
                <div class="filters">

                    <div>
                        <label>From Date</label>
                        <input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>" />
                    </div>

                    <div>
                        <label>To Date</label>
                        <input type="date" name="to" value="<?php echo htmlspecialchars($to); ?>" />
                    </div>

                    <div>
                        <label>Status</label>
                        <select name="status">
                            <option value="all" <?php echo ($status==='all')?'selected':''; ?>>All</option>
                            <option value="paid" <?php echo ($status==='paid')?'selected':''; ?>>Paid</option>
                            <option value="pending" <?php echo ($status==='pending')?'selected':''; ?>>Pending</option>
                        </select>
                    </div>

                    <div>
                        <button class="btn btn-primary" type="submit" style="width:100%;">Search</button>
                    </div>

                </div>
            </form>
        </div>

        <div class="card">
            <div class="summary">

                <div class="box">
                    <div class="label">Total Orders</div>
                    <div class="value"><?php echo (int)$summary["total_orders"]; ?></div>
                </div>

                <div class="box">
                    <div class="label">Total Amount</div>
                    <div class="value">₹<?php echo number_format((float)$summary["total_amount"], 2); ?></div>
                </div>

                <div class="box">
                    <div class="label">Total Advance</div>
                    <div class="value">₹<?php echo number_format((float)$summary["total_advance"], 2); ?></div>
                </div>

                <div class="box">
                    <div class="label">Total Balance</div>
                    <div class="value">₹<?php echo number_format((float)$summary["total_balance"], 2); ?></div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="page-title" style="font-size:16px; margin-bottom:10px;">Order List</div>

            <?php if (count($rows) === 0) { ?>
                <div style="padding:12px; color:#666;">No data found for selected date range.</div>
            <?php } else { ?>
<div class="table-wrap">
    
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Total</th>
                            <th>Advance</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $sr = 1;
                        foreach ($rows as $r) {
                            $statusClass = ($r['status'] === 'paid') ? 'paid' : 'pending';
                        ?>
                            <tr>
                                <td><?php echo $sr++; ?></td>
                                <td>#<?php echo (int)$r['order_id']; ?></td>
                                <td><?php echo htmlspecialchars($r['order_date']); ?></td>
                                <td><?php echo htmlspecialchars($r['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($r['customer_mobile']); ?></td>
                                <td>₹<?php echo number_format((float)$r['total_amount'], 2); ?></td>
                                <td>₹<?php echo number_format((float)$r['advance'], 2); ?></td>
                                <td>₹<?php echo number_format((float)$r['balance'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo strtoupper($r['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-dark" style="padding:8px 10px; font-size:12px;"
                                       href="order_view.php?id=<?php echo (int)$r['order_id']; ?>">
                                       View
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
                </div>

            <?php } ?>
        </div>

    </div>

</body>
</html>
