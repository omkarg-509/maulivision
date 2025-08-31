<?php

class Auth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!self::isLoggedIn()) {
            $base = defined('BASE_URL') ? BASE_URL : '/';
            header("Location: {$base}auth/logout");
            exit;
        }
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['superadmin']);
    }

    public static function user()
    {
        return $_SESSION['superadmin'] ?? null;
    }

    public static function logout()
    {
        session_start();
        session_destroy();

        if (isset($_COOKIE['superadmin'])) {
            setcookie("superadmin", "", time() - 3600, "/");
        }

    $base = defined('BASE_URL') ? BASE_URL : '/';
    header("Location: {$base}auth/login");
        exit;
    }
}
