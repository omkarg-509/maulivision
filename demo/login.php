<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Already logged in? go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please enter username and password.";
    } else {

        $stmt = $conn->prepare("SELECT id, name, username, password_hash, role FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $isValid = false;
            $needsRehash = false;
            $stored = $user['password_hash'] ?? '';

            if ($stored !== '' && password_verify($password, $stored)) {
                $isValid = true;
                $needsRehash = password_needs_rehash($stored, PASSWORD_DEFAULT);
            } elseif ($stored !== '' && hash_equals($stored, $password)) { // legacy plain-text fallback
                $isValid = true;
                $needsRehash = true;
            }

            if ($isValid) {
                if ($needsRehash) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmtUpdate = $conn->prepare("UPDATE users SET password_hash=? WHERE id=?");
                    $stmtUpdate->bind_param("si", $newHash, $user['id']);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                }

                login_user($user);

                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Invalid username or password.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Door Maker App</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6fb;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 14px;
            padding: 26px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .app-title {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 6px;
            color: #111;
        }
        .app-subtitle {
            font-size: 14px;
            text-align: center;
            color: #666;
            margin-bottom: 18px;
        }
        .error-box {
            background: #ffe5e5;
            color: #b10000;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 14px;
        }
        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #333;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 12px 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 15px;
            margin-bottom: 14px;
        }
        input:focus {
            border-color: #4f46e5;
        }
        .btn {
            width: 100%;
            border: none;
            padding: 12px 14px;
            border-radius: 10px;
            background: #4f46e5;
            color: white;
            font-size: 16px;
            cursor: pointer;
            font-weight: 700;
        }
        .btn:hover {
            opacity: 0.95;
        }
        .footer {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #777;
        }
        .hint {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
            color: #555;
        }
        .hint b {
            color: #111;
        }
    </style>
</head>
<body>

    <div class="login-card">

        <div class="app-title">Door Maker Billing</div>
        <div class="app-subtitle">Login to continue</div>

        <?php if ($error !== "") { ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <form method="POST" action="login.php" autocomplete="off">

            <?php echo csrf_field(); ?>

            <label>Username / Mobile</label>
            <input type="text" name="username" placeholder="Enter username or mobile"
                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required />

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required />

            <button type="submit" class="btn">Login</button>

        </form>

        <div class="hint">
            Demo Login: <b>admin</b> | Password: <b>12345</b>
        </div>

        <div class="footer">© 2026 Door Maker App</div>

    </div>

</body>
</html>
