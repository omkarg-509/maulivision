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
        i.pdf_path,

        o.id as order_id,
        o.order_date,
        o.total_amount,
        o.advance,
        o.balance,
        o.status,
        o.notes,

        c.id as customer_id,
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

$statusClass = ($data['status'] === 'paid') ? 'paid' : 'pending';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice View - Door Maker App</title>

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
            font-weight: 800;
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

        .print-box {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media(max-width: 850px) {
            .grid { grid-template-columns: 1fr; }
        }

        /* Print view */
        @media print {
            body { background: #fff; }
            .topbar, .header-row .btn, .print-hide { display: none !important; }
            .container { padding: 0; max-width: 100%; }
            .card { box-shadow: none; border-radius: 0; margin-bottom: 0; }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Invoice View</div>
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="invoices_list.php">Invoice List</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Invoice: <?php echo htmlspecialchars($data['invoice_no']); ?></div>

            <div class="print-box">
                <a class="btn btn-dark" href="invoices_list.php">← Back</a>
                <a class="btn btn-primary" href="whatsapp_invoice.php?id=<?php echo (int)$data['invoice_id']; ?>">WhatsApp</a>
                <button class="btn btn-dark" onclick="window.print()">Print</button>
            </div>
        </div>

        <div class="card">
            <div class="grid">
                <div>
                    <div class="label">Invoice No</div>
                    <div class="value"><?php echo htmlspecialchars($data['invoice_no']); ?></div>
                </div>

                <div>
                    <div class="label">Invoice Date</div>
                    <div class="value"><?php echo htmlspecialchars($data['invoice_date']); ?></div>
                </div>

                <div>
                    <div class="label">Order ID</div>
                    <div class="value">#<?php echo (int)$data['order_id']; ?></div>
                </div>

                <div>
                    <div class="label">Order Date</div>
                    <div class="value"><?php echo htmlspecialchars($data['order_date']); ?></div>
                </div>

                <div>
                    <div class="label">Customer</div>
                    <div class="value"><?php echo htmlspecialchars($data['customer_name']); ?></div>
                </div>

                <div>
                    <div class="label">Mobile</div>
                    <div class="value"><?php echo htmlspecialchars($data['customer_mobile']); ?></div>
                </div>

                <div>
                    <div class="label">Address</div>
                    <div class="value"><?php echo htmlspecialchars($data['customer_address'] ?? ''); ?></div>
                </div>

                <div>
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="badge <?php echo $statusClass; ?>">
                            <?php echo strtoupper($data['status']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <?php if (!empty($data['notes'])) { ?>
                <div style="margin-top:12px;">
                    <div class="label">Notes</div>
                    <div class="value"><?php echo htmlspecialchars($data['notes']); ?></div>
                </div>
            <?php } ?>
        </div>

        <div class="card">
            <div class="page-title" style="font-size:16px; margin-bottom:10px;">Items</div>

            <?php if (count($items) === 0) { ?>
                <div style="padding:12px; color:#666;">No items found.</div>
            <?php } else { ?>

                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Width</th>
                            <th>Height</th>
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
                                <td><?php echo htmlspecialchars($it['brand'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($it['width'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($it['height'] ?? ''); ?></td>
                                <td><?php echo (int)$it['qty']; ?></td>
                                <td>₹<?php echo number_format((float)$it['rate'], 2); ?></td>
                                <td>₹<?php echo number_format((float)$it['amount'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <?php } ?>
        </div>

        <div class="card">
            <div class="page-title" style="font-size:16px; margin-bottom:10px;">Payment Summary</div>

            <div class="grid">
                <div>
                    <div class="label">Total Amount</div>
                    <div class="value">₹<?php echo number_format((float)$data['total_amount'], 2); ?></div>
                </div>

                <div>
                    <div class="label">Advance</div>
                    <div class="value">₹<?php echo number_format((float)$data['advance'], 2); ?></div>
                </div>

                <div>
                    <div class="label">Balance</div>
                    <div class="value">₹<?php echo number_format((float)$data['balance'], 2); ?></div>
                </div>

                <div>
                    <div class="label">Payment Status</div>
                    <div class="value">
                        <span class="badge <?php echo $statusClass; ?>">
                            <?php echo strtoupper($data['status']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card print-hide">
            <div class="page-title" style="font-size:16px; margin-bottom:10px;">PDF</div>
            <div style="color:#444; line-height:1.6;">
                सध्या हा invoice print-friendly आहे.  
                पुढे आपण TCPDF/Dompdf वापरून PDF generate करून save करू.
            </div>
        </div>

    </div>

</body>
</html>
