<?php
function require_login(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_name'] = $user['name'] ?? '';
    $_SESSION['user_username'] = $user['username'] ?? '';
    $_SESSION['user_role'] = $user['role'] ?? '';
}

function logout_user(): void
{
    session_unset();
    session_destroy();
}

function current_user_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}
