<?php
// Include the database configuration file
require_once __DIR__ . '/config.php';
$conn = (new Database())->getConnection();
// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM vendor WHERE username = ?");
        $stmt->execute([$username]);
        $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vendor) {
            $vendor_id = $vendor['id'];
            $hashed_password = $vendor['password'];

            if (password_verify($password, $hashed_password)) {
                // Successful login
                session_start();
                $_SESSION['vendor_id'] = $vendor_id;
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter username and password.";
    }
}
?>

<!-- Simple login form -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>