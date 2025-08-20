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
        if (!isset($_SESSION['vendor'])) {
            // Check if vendor id is in cookie
            if (isset($_COOKIE['vendor'])) {
                $vendorId = $_COOKIE['vendor'];
                try {
                    $pdo = new PDO('mysql:host=localhost;dbname=u367009900_milk_dairy', 'u367009900_milk_dairy', 'AC]WO/mL9');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->prepare("SELECT * FROM vendor WHERE id = ?");
                    $stmt->execute([$vendorId]);
                    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($vendor) {
                        $_SESSION['vendor'] = $vendor;
                        return true;
                    } else {
                        // Only logout if not already on login page
                        if (strpos($_SERVER['REQUEST_URI'], '/public/auth/login') === false) {
                            self::logout();
                        }
                    }
                } catch (PDOException $e) {
                    die('Database connection failed: ' . $e->getMessage());
                }
            } else {
                if (strpos($_SERVER['REQUEST_URI'], '/public/auth/login') === false) {
                    self::logout();
                }
            }
        }
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
