<?php
session_start();
require_once 'db.php'; // Make sure this file sets up $pdo as a PDO instance

if (!isset($pdo) || !$pdo) {
    die('Database connection failed. Please check your db.php configuration.');
}

// Redirect to dashboard if already logged in
if (isset($_SESSION['vendor_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check credentials against vendor table using PDO
    $stmt = $pdo->prepare('SELECT id, password FROM vendor WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vendor && password_verify($password, $vendor['password'])) {
        $_SESSION['vendor_id'] = $vendor['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MCMS</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-container {
            width: 300px; margin: 100px auto; padding: 20px;
            background: #fff; border-radius: 5px; box-shadow: 0 0 10px #ccc;
        }
        .login-container h2 { text-align: center; }
        .error { color: red; text-align: center; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 8px; margin: 8px 0; box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%; padding: 8px; background: #007bff; color: #fff;
            border: none; border-radius: 3px; cursor: pointer;
        }
        input[type="submit"]:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required autofocus value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>