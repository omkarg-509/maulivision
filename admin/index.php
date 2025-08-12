<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: login.php');
    exit();
}
$layout = new PageLayout();
$layout->header("Admin Dashboard");
// ... page content ...
$layout->footer();
?>