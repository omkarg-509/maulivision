<?php
// Include the database configuration file
require_once 'config.php';
$conn = (new Database())->getConnection();

// Signup logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM vendor WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already taken.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO vendor (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                // Registration successful
                session_start();
                $_SESSION['vendor_id'] = $conn->lastInsertId();
                header("Location: index.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    } else {
        $error = "Please enter username and password.";
    }
}
?>
