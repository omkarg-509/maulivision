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
        // Enforce subscription (allow auth, subscription, and verification endpoints)
        $path = $_GET['url'] ?? '';
        $allowed = ['subscription','auth/login','auth/logout','auth/register','subscription/verify','subscription/createOrder'];
        if (!isset($_SESSION['has_active_subscription'])) {
            // Lazy load subscription status
            $vendor = $_SESSION['vendor'];
            require_once '../app/models/Subscription.php';
            $subModel = new Subscription();
            $active = $subModel->getActiveByVendor($vendor['id']);
            if ($active) {
                $_SESSION['has_active_subscription'] = true;
            }
        }
        if (empty($_SESSION['has_active_subscription'])) {
            $isAllowed = false;
            foreach ($allowed as $a) { if (strpos($path, $a) === 0) { $isAllowed = true; break; } }
            if (!$isAllowed) {
                header('Location: '.BASE_URL.'subscription');
                exit;
            }
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
