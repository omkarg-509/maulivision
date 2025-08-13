<?php
include 'db.php'; // Include database connection
// Handle form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Simple validation
    if (empty($username) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Database error.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Registration</title>
</head>
<body>
    <h2>Register</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>