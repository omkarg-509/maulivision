<?php

class Auth
{
    public static function check()
    {
       
         Auth::isLoggedIn(); // Check if user is already logged in
        if (!isset($_SESSION['vendor'])) {
            header("Location: /public/dashboard");
            exit;
        }
    }
    public static function isLoggedIn()
    {
        session_start();
        
        return isset($_SESSION['vendor']);
    }

    public static function user()
    {
        return $_SESSION['vendor'] ?? null;
    }

  public static function logout()
{
    session_start();

    // Destroy all session data
    session_destroy();

    // Optional: Clear cookies
    if (isset($_COOKIE['vendor'])) {
        setcookie("vendor", "", time() - 3600, "/");
    }

    // Redirect to login
    header("Location: /public/auth/login");
    exit;
}

}
