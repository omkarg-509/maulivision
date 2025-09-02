<?php

class Auth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!self::isLoggedIn()) {
            header("Location: /public/auth/logout");
            exit;
        }
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['admin']);
    }

    public static function user()
    {
        return $_SESSION['admin'] ?? null;
    }

    public static function logout()
    {
        session_start();
        session_destroy();

        if (isset($_COOKIE['admin'])) {
            setcookie("admin", "", time() - 3600, "/");
        }

        header("Location: /public/auth/login");
        exit;
    }
}
