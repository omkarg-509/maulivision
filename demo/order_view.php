<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: order_list.php");
    exit;
}

// Fetch order + customer
$stmt = $conn->prepare("
    SELECT o.*, c.name as customer_name, c.mobile as customer_mobile, c.address as customer_address
    FROM orders o
    INNER JOIN customers c ON c.id = o.customer_id
    WHERE o.id = ?
    LIMIT 1
");
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

// Fetch items
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

$statusClass = ($order['status'] === 'paid') ? 'paid' : 'pending';
$work_status = $order['work_status'] ?? 'new';

$workClass = 'ws-new';

if ($work_status === 'in_progress') $workClass = 'ws-progress';
if ($work_status === 'ready') $workClass = 'ws-ready';
if ($work_status === 'delivered') $workClass = 'ws-delivered';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Order - Door Maker App</title>

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
            max-width: 1100px;
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
        }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-dark { background: #111827; color: #fff; }
        .btn-danger { background: #ef4444; color: #fff; }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            margin-bottom: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .label {
            font-size: 13px;
            color: #666;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .value {
            font-size: 15px;
            color: #111;
            font-weight: 900;
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
.ws-new { background:#e0f2fe; color:#075985; }        /* NEW */
.ws-progress { background:#fff7ed; color:#9a3412; }  /* IN PROGRESS */
.ws-ready { background:#fef9c3; color:#854d0e; }     /* READY */
.ws-delivered { background:#dcfce7; color:#166534; } /* DELIVERED */

        @media(max-width: 850px) {
            .grid { grid-template-columns: 1fr; }
        }
      
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
        <div class="title">Order View</div>
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <a href="order_list.php">Order List</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Order #<?php echo (int)$order['id']; ?></div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="order_list.php">← Back</a>
                <a class="btn btn-primary" href="order_edit.php?id=<?php echo (int)$order['id']; ?>">Edit</a>
                <a class="btn btn-danger"
                   href="order_delete.php?id=<?php echo (int)$order['id']; ?>"
                   onclick="return confirm('Delete this order?');">
                   Delete
                </a>
            </div>
        </div>

        <div class="card">
            <div class="grid">
                <div>
                    <div class="label">Customer</div>
                    <div class="value"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                </div>

                <div>
                    <div class="label">Mobile</div>
                    <div class="value"><?php echo htmlspecialchars($order['customer_mobile']); ?></div>
                </div>

                <div>
                    <div class="label">Order Date</div>
                    <div class="value"><?php echo htmlspecialchars($order['order_date']); ?></div>
                </div>

                <div>
                    <div class="label">Payment Status</div>
                    <div class="value">
                        <span class="badge <?php echo $statusClass; ?>">
                            <?php echo strtoupper($order['status']); ?>
                        </span>
                    </div>
                </div>
<div>
    <div class="label">Work Status</div>
    <div class="value">
   <span class="badge <?php echo $workClass; ?>">
    <?php echo strtoupper(str_replace('_', ' ', $work_status)); ?>
</span>

    </div>
</div>

                <div>
                    <div class="label">Total Amount</div>
                    <div class="value">₹<?php echo number_format((float)$order['total_amount'], 2); ?></div>
                </div>

                <div>
                    <div class="label">Advance</div>
                    <div class="value">₹<?php echo number_format((float)$order['advance'], 2); ?></div>
                </div>

                <div>
                    <div class="label">Balance</div>
                    <div class="value">₹<?php echo number_format((float)$order['balance'], 2); ?></div>
                </div>

                <div>
                    <div class="label">Address</div>
                    <div class="value"><?php echo htmlspecialchars($order['customer_address'] ?? ''); ?></div>
                </div>
            </div>

            <?php if (!empty($order['notes'])) { ?>
                <div style="margin-top:12px;">
                    <div class="label">Notes</div>
                    <div class="value"><?php echo htmlspecialchars($order['notes']); ?></div>
                </div>
            <?php } ?>
        </div>

        <div class="card">
            <div class="page-title" style="font-size:16px; margin-bottom:10px;">Order Items</div>

            <?php if (count($items) === 0) { ?>
                <div style="padding:12px; color:#666;">No items found.</div>
            <?php } else { ?>
  <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Door Type</th>
                            <th>Width/Height</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $it) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($it['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($it['door_type'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($it['width_height'] ?? ''); ?></td>
                                <td><?php echo (int)$it['qty']; ?></td>
                                <td>₹<?php echo number_format((float)$it['rate'], 2); ?></td>
                                <td>₹<?php echo number_format((float)$it['amount'], 2); ?></td>
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
