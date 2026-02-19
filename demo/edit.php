<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: list.php");
    exit;
}

// Fetch customer
$stmt = $conn->prepare("SELECT * FROM customers WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows !== 1) {
    $stmt->close();
    header("Location: list.php");
    exit;
}

$customer = $result->fetch_assoc();
$stmt->close();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Customer - Door Maker App</title>

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
            max-width: 900px;
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
        input, textarea {
            width: 100%;
            padding: 12px 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 14px;
            margin-bottom: 14px;
        }
        textarea { min-height: 90px; resize: vertical; }

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

        .btn-submit {
            width: 100%;
            border: none;
            padding: 12px 14px;
            border-radius: 10px;
            background: #4f46e5;
            color: white;
            font-size: 16px;
            cursor: pointer;
            font-weight: 900;
        }
        .btn-submit:hover { opacity: 0.95; }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media(max-width: 700px) {
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="topbar">
        <div class="title">Edit Customer</div>
        <div style="display:flex; gap:12px; align-items:center;">
            <a href="list.php">Customer List</a>
            <a href="logout.php" style="background:#ef4444; padding:8px 12px; border-radius:10px;">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="header-row">
            <div class="page-title">Update Customer</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-dark" href="list.php">← Back</a>
                <a class="btn btn-danger"
                   href="customer_delete.php?id=<?php echo (int)$customer['id']; ?>"
                   onclick="return confirm('Delete this customer?');">
                   Delete
                </a>
            </div>
        </div>

        <div class="card">

            <?php if ($success === "1") { ?>
                <div class="msg-success">Customer updated successfully!</div>
            <?php } ?>

            <?php if ($error !== "") { ?>
                <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>

            <form method="POST" action="customer_update.php" autocomplete="off">

                <input type="hidden" name="id" value="<?php echo (int)$customer['id']; ?>" />

                <div class="row">
                    <div>
                        <label>Customer Name *</label>
                        <input type="text" name="name" required
                               value="<?php echo htmlspecialchars($customer['name']); ?>" />
                    </div>

                    <div>
                        <label>Mobile Number *</label>
                        <input type="text" name="mobile" required
                               value="<?php echo htmlspecialchars($customer['mobile']); ?>" />
                    </div>
                </div>

                <label>Address</label>
                <textarea name="address"><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea>

                <button type="submit" class="btn-submit">Update Customer</button>

            </form>

        </div>

    </div>

</body>
</html>
