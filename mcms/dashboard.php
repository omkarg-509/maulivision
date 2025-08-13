<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['vendor_id'])) {
    header('Location: login.php');
    exit();
}

// Example staff list (replace with DB query if needed)
$staff = [
    'John Doe',
    'Jane Smith',
    'Alice Johnson',
    'Bob Lee'
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Appointment Form</title>
</head>
<body>
    <h2>Enter Appointment Details</h2>
    <form method="post" action="save_appointment.php">
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