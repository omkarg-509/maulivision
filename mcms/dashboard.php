<?php
session_start();
require_once 'db.php'; // Include database connection
// Check if user is logged in
if (!isset($_SESSION['vendor_id'])) {
    header('Location: login.php');
    exit();
}

// Define the database name
$dbname = 'u367009900_maulivision'; // <-- Replace with your actual database name

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
$conn->select_db($dbname);

// Create customer table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    number VARCHAR(20),
    staff VARCHAR(100),
    time TIME,
    amount DECIMAL(10,2)
)");

// Example staff list (replace with DB query if needed)
$staff = [
    'John Doe',
    'Jane Smith',
    'Alice Johnson',
    'Bob Lee'
];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $amount = floatval($_POST['amount']);
    $time = $_POST['time'];
    $staff_name = $_POST['staff'];
    $amount = $_POST['amount'];

    // Check if customer exists
    $stmt = $conn->prepare("SELECT id FROM customer WHERE name=? AND number=?");
    $stmt->bind_param("ss", $name, $number);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Customer already exists!";
    } else {
        // Insert new customer
        $stmt = $conn->prepare("INSERT INTO customer (name, number, staff, time, amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $name, $number, $staff_name, $time, $amount);
        if ($stmt->execute()) {
            $message = "Appointment saved successfully!";
        } else {
            $message = "Error saving appointment.";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Appointment Form</title>
</head>
<body>
    <h2>Enter Appointment Details</h2>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="number">Number:</label>
        <input type="text" name="number" id="number" required><br><br>

        <label for="time">Select Time:</label>
        <input type="time" name="time" id="time" required><br><br>

        <label for="staff">Staff Name:</label>
        <select name="staff" id="staff" required>
            <option value="">Select Staff</option>
            <?php foreach ($staff as $s): ?>
                <option value="<?php echo htmlspecialchars($s); ?>"><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" min="0" required><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>