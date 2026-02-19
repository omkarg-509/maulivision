<?php
session_start();

// echo password_hash("12345", PASSWORD_DEFAULT);

// जर आधीच login असेल तर dashboard ला जा
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard");
    exit;
}

require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please enter username and password.";
    } else {

        // SQL Injection safe (Prepared Statement)
        $stmt = $conn->prepare("SELECT id, name, username, password_hash, role FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {

            $user = $result->fetch_assoc();

            // if (password_verify($password, $user['password_hash'])) {
                if ($password==$user['password_hash']) {

                // Login Success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];

                header("Location: dashboard");
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