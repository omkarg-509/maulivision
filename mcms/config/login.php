<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Include the database configuration file
require_once 'config.php';
$error = ''; // Initialize error variable

try {
    $conn = (new Database())->getConnection();
} catch (Exception $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

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
