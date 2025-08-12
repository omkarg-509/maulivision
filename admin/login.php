<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Replace with your actual admin credentials
    $admin_user = 'omkarg509';
    $admin_pass = 'omkarg509';

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">s
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-container {
            width: 300px; margin: 100px auto; padding: 20px;
            background: #fff; border-radius: 5px; box-shadow: 0 0 10px #ccc;
        }
        .login-container h2 { text-align: center; }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box;
        }
        .login-container input[type="submit"] {
            width: 100%; padding: 10px; background: #007bff; color: #fff;
            border: none; border-radius: 3px; cursor: pointer;
        }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
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