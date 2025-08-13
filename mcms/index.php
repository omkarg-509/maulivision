<?php
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Dummy credentials for demonstration
    $valid_user = 'admin';
    $valid_pass = 'password123';

    if ($username === $valid_user && $password === $valid_pass) {
        $_SESSION['user_id'] = $username;
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
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>