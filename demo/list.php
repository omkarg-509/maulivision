<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

// Search
$search = trim($_GET['search'] ?? '');

// Pagination
$limit = 20;
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Fetch customers
$customers = [];
$totalRows = 0;

if ($search !== "") {

    $like = "%".$search."%";

    // total
    $stmtCount = $conn->prepare("SELECT COUNT(*) as total FROM customers WHERE name LIKE ? OR mobile LIKE ?");
    $stmtCount->bind_param("ss", $like, $like);
    $stmtCount->execute();
    $totalRows = (int)$stmtCount->get_result()->fetch_assoc()['total'];
    $stmtCount->close();

    // data
    $stmt = $conn->prepare("SELECT * FROM customers WHERE name LIKE ? OR mobile LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    // total
    $qCount = $conn->query("SELECT COUNT(*) as total FROM customers");
    if ($qCount) {
        $totalRows = (int)$qCount->fetch_assoc()['total'];
    }

    // data
    $stmt = $conn->prepare("SELECT * FROM customers ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
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
    <title>Customers - Door Maker App</title>

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
        .btn-danger { background: #ef4444; color: #fff; }

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
        .search-row button {
            border: none;
            cursor: pointer;
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
            font-weight: 800;
            color: #333;
        }

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

        @media(max-width: 700px) {
            th:nth-child(4), td:nth-child(4) { display: none; }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Customers</div>
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Customer List</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="dashboard.php">← Back</a>
                <a class="btn btn-primary" href="add.php">+ Add Customer</a>
            </div>
        </div>

        <div class="card">

            <form method="GET" class="search-row">
                <input type="text" name="search" placeholder="Search by name or mobile..."
                       value="<?php echo htmlspecialchars($search); ?>" />
                <button class="btn btn-primary" type="submit">Search</button>
                <a class="btn btn-dark" href="list.php">Reset</a>
            </form>

            <?php if (count($customers) === 0) { ?>
                <div class="empty">No customers found.</div>
            <?php } else { ?>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $sr = $offset + 1;
                        foreach ($customers as $c) {
                        ?>
                            <tr>
                                <td><?php echo $sr++; ?></td>
                                <td><?php echo htmlspecialchars($c['name']); ?></td>
                                <td><?php echo htmlspecialchars($c['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($c['address'] ?? ''); ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-primary small" href="edit.php?id=<?php echo (int)$c['id']; ?>">Edit</a>
                                        <a class="btn btn-danger small"
                                           href="customer_delete.php?id=<?php echo (int)$c['id']; ?>"
                                           onclick="return confirm('Delete this customer?');">
                                           Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

                <div class="pagination">
                    <?php
                    $baseUrl = "list.php?search=" . urlencode($search) . "&page=";

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
