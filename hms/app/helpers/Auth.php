<?php

class Auth
{
    public static function check()
    {
        session_start();
        //  session_destroy();
        if (!self::isLoggedIn()) {
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
        // Attempt to mark vendor inactive
        try {
            if (!empty($_SESSION['vendor']['id'])) {
                require_once __DIR__ . '/../models/User.php';
                $userModel = new User();
                $userModel->setStatusById((string)$_SESSION['vendor']['id'], 'inactive');
            }
        } catch (\Throwable $e) {}

        session_destroy();

        if (isset($_COOKIE['vendor'])) {
            setcookie("vendor", "", time() - 3600, "/");
        }

        header("Location: /public/auth/login");
        exit;
    }
}
