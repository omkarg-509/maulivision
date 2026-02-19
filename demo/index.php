<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

require_once 'db.php';

// Counts
$totalCustomers = 0;
$totalOrders = 0;
$totalPending = 0;
$totalPaid = 0;
$todayOrders = 0;

// Total customers
$q1 = $conn->query("SELECT COUNT(*) as total FROM customers");
if ($q1) {
    $totalCustomers = (int)$q1->fetch_assoc()['total'];
}

// Total orders
$q2 = $conn->query("SELECT COUNT(*) as total FROM orders");
if ($q2) {
    $totalOrders = (int)$q2->fetch_assoc()['total'];
}

// Pending orders
$q3 = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='pending'");
if ($q3) {
    $totalPending = (int)$q3->fetch_assoc()['total'];
}

// Paid orders
$q4 = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='paid'");
if ($q4) {
    $totalPaid = (int)$q4->fetch_assoc()['total'];
}

// Today orders
$today = date("Y-m-d");
$q5 = $conn->query("SELECT COUNT(*) as total FROM orders WHERE DATE(order_date) = '$today'");
if ($q5) {
    $todayOrders = (int)$q5->fetch_assoc()['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Door Maker App</title>

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
        .topbar .user {
            font-size: 14px;
            opacity: 0.9;
        }

        .container {
            padding: 18px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .welcome {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #111;
        }
        .sub {
            color: #555;
            margin-bottom: 16px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }
        .card .label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .card .value {
            font-size: 28px;
            font-weight: 800;
            color: #111;
        }

        .menu {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .menu a {
            text-decoration: none;
            background: #fff;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            color: #111;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.2s;
        }
        .menu a:hover {
            transform: translateY(-2px);
        }
        .menu span {
            color: #4f46e5;
            font-weight: 800;
        }

        .footer {
            text-align: center;
            padding: 16px;
            color: #777;
            font-size: 13px;
            margin-top: 18px;
        }

        .logout {
            color: #fff;
            text-decoration: none;
            background: #ef4444;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
        }

        @media(max-width: 900px) {
            .grid { grid-template-columns: repeat(2, 1fr); }
            .menu { grid-template-columns: repeat(1, 1fr); }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Door Maker Billing</div>
        <div style="display:flex; gap:10px; align-items:center;">
            <div class="user">
                Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? "User"); ?>
            </div>
            <a class="logout" href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="welcome">Dashboard</div>
        <div class="sub">Quick summary of your work</div>

        <div class="grid">
            <div class="card">
                <div class="label">Total Customers</div>
                <div class="value"><?php echo $totalCustomers; ?></div>
            </div>

            <div class="card">
                <div class="label">Total Orders</div>
                <div class="value"><?php echo $totalOrders; ?></div>
            </div>

            <div class="card">
                <div class="label">Pending Orders</div>
                <div class="value"><?php echo $totalPending; ?></div>
            </div>

            <div class="card">
                <div class="label">Paid Orders</div>
                <div class="value"><?php echo $totalPaid; ?></div>
            </div>
        </div>

        <div class="menu">
            <a href="list.php">
                Customers <span>→</span>
            </a>

            <a href="order_list.php">
                Orders <span>→</span>
            </a>

            <a href="invoices_list.php">
                Invoices <span>→</span>
            </a>

            <a href="orders_add.php">
                Add New Order <span>+</span>
            </a>

            <a href="customer_add.php">
                Add Customer <span>+</span>
            </a>

            <a href="collection.php">
                Reports <span>→</span>
            </a>
        </div>

        <div class="footer">
            © <?php echo date("Y"); ?> Door Maker App
        </div>

    </div>

</body>
</html>
