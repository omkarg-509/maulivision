<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once'db.php';

// Search
$search = trim($_GET['search'] ?? '');

// Pagination
$limit = 20;
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Fetch invoices with customer + order
$invoices = [];
$totalRows = 0;

if ($search !== "") {

    $like = "%".$search."%";

    // total
    $stmtCount = $conn->prepare("
        SELECT COUNT(*) as total
        FROM invoices i
        INNER JOIN orders o ON o.id = i.order_id
        INNER JOIN customers c ON c.id = o.customer_id
        WHERE i.invoice_no LIKE ?
           OR c.name LIKE ?
           OR c.mobile LIKE ?
    ");
    $stmtCount->bind_param("sss", $like, $like, $like);
    $stmtCount->execute();
    $totalRows = (int)$stmtCount->get_result()->fetch_assoc()['total'];
    $stmtCount->close();

    // data
    $stmt = $conn->prepare("
        SELECT 
            i.id,
            i.invoice_no,
            i.invoice_date,
            i.pdf_path,
            o.id as order_id,
            o.total_amount,
            o.advance,
            o.balance,
            o.status,
            c.name as customer_name,
            c.mobile as customer_mobile
        FROM invoices i
        INNER JOIN orders o ON o.id = i.order_id
        INNER JOIN customers c ON c.id = o.customer_id
        WHERE i.invoice_no LIKE ?
           OR c.name LIKE ?
           OR c.mobile LIKE ?
        ORDER BY i.id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("sssii", $like, $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    // total
    $qCount = $conn->query("SELECT COUNT(*) as total FROM invoices");
    if ($qCount) {
        $totalRows = (int)$qCount->fetch_assoc()['total'];
    }

    // data
    $stmt = $conn->prepare("
        SELECT 
            i.id,
            i.invoice_no,
            i.invoice_date,
            i.pdf_path,
            o.id as order_id,
            o.total_amount,
            o.advance,
            o.balance,
            o.status,
            c.name as customer_name,
            c.mobile as customer_mobile
        FROM invoices i
        INNER JOIN orders o ON o.id = i.order_id
        INNER JOIN customers c ON c.id = o.customer_id
        ORDER BY i.id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }
}
$stmt->close();

$totalPages = (int)ceil($totalRows / $limit);
if ($totalPages < 1) $totalPages = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoices - Door Maker App</title>

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
        }

        .search-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .search-row input {
            flex: 1;
            min-width: 220px;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 14px;
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

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .small {
            font-size: 12px;
            padding: 8px 10px;
            border-radius: 10px;
        }

        .pagination {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 14px;
        }
        .pagination a {
            padding: 8px 12px;
            border-radius: 10px;
            background: #fff;
            text-decoration: none;
            color: #111;
            font-weight: 800;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border: 1px solid #eee;
        }
        .pagination a.active {
            background: #4f46e5;
            color: #fff;
            border: none;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        @media(max-width: 900px) {
            th:nth-child(6), td:nth-child(6) { display: none; }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Invoices</div>
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="order_list.php">Orders</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Invoice List</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="dashboard.php">← Back</a>
                <a class="btn btn-primary" href="invoice_create.php">+ Create Invoice</a>
            </div>
        </div>

        <div class="card">

            <form method="GET" class="search-row">
                <input type="text" name="search" placeholder="Search invoice no, customer name, mobile..."
                       value="<?php echo htmlspecialchars($search); ?>" />
                <button class="btn btn-primary" type="submit">Search</button>
                <a class="btn btn-dark" href="invoices_list.php">Reset</a>
            </form>

            <?php if (count($invoices) === 0) { ?>
                <div class="empty">No invoices found.</div>
            <?php } else { ?>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Total</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $sr = $offset + 1;
                        foreach ($invoices as $inv) {
                            $statusClass = ($inv['status'] === 'paid') ? 'paid' : 'pending';
                        ?>
                            <tr>
                                <td><?php echo $sr++; ?></td>
                                <td><?php echo htmlspecialchars($inv['invoice_no']); ?></td>
                                <td><?php echo htmlspecialchars($inv['invoice_date']); ?></td>
                                <td><?php echo htmlspecialchars($inv['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($inv['customer_mobile']); ?></td>
                                <td>₹<?php echo number_format((float)$inv['total_amount'], 2); ?></td>
                                <td>₹<?php echo number_format((float)$inv['balance'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo strtoupper($inv['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">

                                        <a class="btn btn-primary small"
                                           href="invoice_view.php?id=<?php echo (int)$inv['id']; ?>">
                                           View
                                        </a>

                                        <?php if (!empty($inv['pdf_path'])) { ?>
                                            <a class="btn btn-dark small" target="_blank"
                                               href="<?php echo htmlspecialchars($inv['pdf_path']); ?>">
                                               PDF
                                            </a>
                                        <?php } ?>

                                        <a class="btn btn-dark small"
                                           href="whatsapp_invoice.php?id=<?php echo (int)$inv['id']; ?>">
                                           WhatsApp
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

                <div class="pagination">
                    <?php
                    $baseUrl = "invoices_list.php?search=" . urlencode($search) . "&page=";

                    if ($page > 1) {
                        echo '<a href="'.$baseUrl.($page-1).'">← Prev</a>';
                    }

                    for ($i = 1; $i <= $totalPages; $i++) {
                        $active = ($i === $page) ? "active" : "";
                        echo '<a class="'.$active.'" href="'.$baseUrl.$i.'">'.$i.'</a>';
                    }

                    if ($page < $totalPages) {
                        echo '<a href="'.$baseUrl.($page+1).'">Next →</a>';
                    }
                    ?>
                </div>

            <?php } ?>

        </div>

    </div>

</body>
</html>
