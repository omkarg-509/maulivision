<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

// Search
$search = trim($_GET['search'] ?? '');

// Pagination
$limit = 20;
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Fetch orders with customer
$orders = [];
$totalRows = 0;

if ($search !== "") {

    $like = "%".$search."%";

    // total
    $stmtCount = $conn->prepare("
        SELECT COUNT(*) as total
        FROM orders o
        INNER JOIN customers c ON c.id = o.customer_id
        WHERE c.name LIKE ? OR c.mobile LIKE ? OR o.status LIKE ? OR o.work_status LIKE ?
    ");
    $stmtCount->bind_param("ssss", $like, $like, $like, $like);
    $stmtCount->execute();
    $totalRows = (int)$stmtCount->get_result()->fetch_assoc()['total'];
    $stmtCount->close();

    // data
    $stmt = $conn->prepare("
        SELECT o.*, c.name as customer_name, c.mobile as customer_mobile
        FROM orders o
        INNER JOIN customers c ON c.id = o.customer_id
        WHERE c.name LIKE ? OR c.mobile LIKE ? OR o.status LIKE ? OR o.work_status LIKE ?
        ORDER BY o.id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ssssii", $like, $like, $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    // total
    $qCount = $conn->query("SELECT COUNT(*) as total FROM orders");
    if ($qCount) {
        $totalRows = (int)$qCount->fetch_assoc()['total'];
    }

    // data
    $stmt = $conn->prepare("
        SELECT o.*, c.name as customer_name, c.mobile as customer_mobile
        FROM orders o
        INNER JOIN customers c ON c.id = o.customer_id
        ORDER BY o.id DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
$stmt->close();

$totalPages = (int)ceil($totalRows / $limit);
if ($totalPages < 1) $totalPages = 1;

// Work status label helper
function workLabel($s) {
    if ($s === "new") return "NEW";
    if ($s === "in_progress") return "IN PROGRESS";
    if ($s === "ready") return "READY";
    if ($s === "delivered") return "DELIVERED";
    return strtoupper($s);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders - Door Maker App</title>

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
            max-width: 1250px;
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

        /* Payment */
        .pay-pending { background: #fff7ed; color: #9a3412; }
        .pay-paid { background: #dcfce7; color: #166534; }

        /* Work */
        .work-new { background: #eff6ff; color: #1d4ed8; }
        .work-progress { background: #fef9c3; color: #854d0e; }
        .work-ready { background: #e0e7ff; color: #3730a3; }
        .work-delivered { background: #dcfce7; color: #166534; }

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

        @media(max-width: 980px) {
            th:nth-child(6), td:nth-child(6) { display: none; }
        }/* Table scroll on mobile */
.table-wrap{
    width:100%;
    overflow-x:auto;
    -webkit-overflow-scrolling:touch;
    border-radius:12px;
}

/* Small screens */
@media(max-width: 980px) {

    .container{
        padding: 12px;
    }

    .topbar{
        flex-wrap: wrap;
        gap: 10px;
        padding: 12px 12px;
    }

    .topbar .title{
        font-size: 16px;
    }

    .page-title{
        font-size: 16px;
    }

    .header-row{
        flex-direction: column;
        align-items: flex-start;
    }

    .header-row > div:last-child{
        width: 100%;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .header-row .btn{
        flex: 1;
        text-align: center;
        min-width: 140px;
    }

    .search-row{
        flex-direction: column;
        align-items: stretch;
    }

    .search-row input{
        width: 100%;
        min-width: unset;
    }

    .search-row button,
    .search-row a{
        width: 100%;
        text-align: center;
    }

    table th, table td{
        white-space: nowrap;
        font-size: 13px;
        padding: 10px;
    }

    /* hide big column (optional) */
    th:nth-child(6), td:nth-child(6) { display: none; }

    .pagination{
        justify-content: center;
    }
}

    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Orders</div>
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Order List</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="dashboard.php">← Back</a>
                <a class="btn btn-primary" href="order_add.php">+ Add Order</a>
            </div>
        </div>

        <div class="card">

            <form method="GET" class="search-row">
                <input type="text" name="search" placeholder="Search by customer, mobile, payment, work..."
                       value="<?php echo htmlspecialchars($search); ?>" />
                <button class="btn btn-primary" type="submit">Search</button>
                <a class="btn btn-dark" href="order_list.php">Reset</a>
            </form>

            <?php if (count($orders) === 0) { ?>
                <div class="empty">No orders found.</div>
            <?php } else { ?>
<div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Order Date</th>
                            <th>Total</th>
                            <th>Advance</th>
                            <th>Balance</th>
                            <th>Payment</th>
                            <th>Work</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $sr = $offset + 1;
                        foreach ($orders as $o) {

                            $payClass = ($o['status'] === 'paid') ? 'pay-paid' : 'pay-pending';

                            $ws = $o['work_status'] ?? 'new';
                            $workClass = "work-new";
                            if ($ws === "in_progress") $workClass = "work-progress";
                            if ($ws === "ready") $workClass = "work-ready";
                            if ($ws === "delivered") $workClass = "work-delivered";
                        ?>
                            <tr>
                                <td><?php echo $sr++; ?></td>
                                <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($o['customer_mobile']); ?></td>
                                <td><?php echo htmlspecialchars($o['order_date']); ?></td>
                                <td>₹<?php echo number_format((float)$o['total_amount'], 2); ?></td>
                                <td>₹<?php echo number_format((float)$o['advance'], 2); ?></td>
                                <td>₹<?php echo number_format((float)$o['balance'], 2); ?></td>

                                <td>
                                    <span class="badge <?php echo $payClass; ?>">
                                        <?php echo strtoupper($o['status']); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?php echo $workClass; ?>">
                                        <?php echo workLabel($ws); ?>
                                    </span>
                                </td>

                                <td>
                                    <a class="btn btn-primary" style="padding:8px 10px; font-size:12px;"
                                       href="order_view.php?id=<?php echo (int)$o['id']; ?>">View</a>

                                    <a class="btn btn-dark" style="padding:8px 10px; font-size:12px;"
                                       href="order_edit.php?id=<?php echo (int)$o['id']; ?>">Edit</a>

                                    <a class="btn btn-dark" style="padding:8px 10px; font-size:12px; background:#ef4444;"
                                       href="order_delete.php?id=<?php echo (int)$o['id']; ?>"
                                       onclick="return confirm('Delete this order?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
                </div>

                <div class="pagination">
                    <?php
                    $baseUrl = "order_list.php?search=" . urlencode($search) . "&page=";

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
