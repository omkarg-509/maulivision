<?php

class Auth
{
    public static function check()
    {
        session_start();
         Auth::isLoggedIn(); // Check if user is already logged in
        if (!isset($_SESSION['vendor'])) {
            header("Location: /public/auth/login");
            exit;
        }
    }
    public static function isLoggedIn(){
         session_start();
        if(isset($_SESSION['vendor']) || isset($_COOKIE['vendor'])){
            $_SESSION['vendor'] = $_SESSION['vendor'] ?? $_COOKIE['vendor'];
            // Optional: Regenerate session ID for security
            header("Location: /public/auth/logout");
            exit;
        }
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
