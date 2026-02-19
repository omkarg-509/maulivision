<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login");
    exit;
}

require_once 'db.php';
?>