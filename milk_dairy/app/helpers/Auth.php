<?php

class Auth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $currentUri = $_SERVER['REQUEST_URI'] ?? '';
        if (!self::isLoggedIn() && strpos($currentUri, '/public/auth/login') === false) {
            header("Location: /public/auth/login");
            exit;
        }
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['vendor']);
    }

    public static function user()
    {
        return $_SESSION['vendor'] ?? null;
    }

    public static function logout()
    {
        session_start();
        session_destroy();

        if (isset($_COOKIE['vendor'])) {
            setcookie("vendor", "", time() - 3600, "/");
        }

        header("Location: /public/auth/login");
        exit;
    }
}
