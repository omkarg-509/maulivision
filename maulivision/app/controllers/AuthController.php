<?php

require_once '../app/helpers/Auth.php';

class AuthController extends Controller
{
    public function index(){
        // Check if user is already logged in and redirect to dashboard
        if ($this->isUserLoggedIn()) {
            $this->redirectToDashboard();
        }
        $this->view('auth/login');
    }

   

    public function login()
    {
        // If cookie exists but session does not, logout and exit
        // if (isset($_COOKIE['vendor']) && (session_status() === PHP_SESSION_NONE || !isset($_SESSION['vendor']))) {
        //     Auth::logout();
        //     exit;
        // }

        // If already logged in, redirect to dashboard
        if ($this->isUserLoggedIn()) {
            $this->redirectToDashboard();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $email_or_number = isset($_POST['email_or_number']) ? trim($_POST['email_or_number']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if (empty($email_or_number) || empty($password)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email or mobile number and password are required.'
                ]);
                exit;
            }

            $userModel = $this->model('User');
            $vendor = $userModel->findByEmailOrNumber($email_or_number);

            // Use password_verify if passwords are hashed
            // if ($vendor && password_verify($password, $vendor['password'])) {
            if ($vendor && $password == $vendor['password']) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['vendor'] = $vendor;
                setcookie("vendor", $vendor['id'], time() + (7 * 24 * 60 * 60), "/");

                echo json_encode([
                    'status' => 'success',
                    'redirect' => BASE_URL . 'dashboard'
                ]);
                exit;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid credentials.'
                ]);
                exit;
            }
        } else {
            $this->view('auth/login');
        }
    }

   

    public function logout()
    {
        Auth::logout();
    }

    /**
     * Check if user is logged in via session or cookie
     */
    private function isUserLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If session is set, redirect to dashboard
        if (isset($_SESSION['vendor']) && !empty($_SESSION['vendor'])) {
            $this->redirectToDashboard();
            return true;
        }

        // Check cookie if session doesn't exist
        if (isset($_COOKIE['vendor']) && !empty($_COOKIE['vendor'])) {
            // Validate cookie by fetching user from database
            $userModel = $this->model('User');
            $vendor = $userModel->findById($_COOKIE['vendor']);
            
            if ($vendor) {
                // Restore session from cookie
                $_SESSION['vendor'] = $vendor;
                $this->redirectToDashboard();
                return true;
            }
        }

        return false;
    }

    /**
     * Redirect to dashboard
     */
    private function redirectToDashboard()
    {
        $redirectUrl = (defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : '/') . 'dashboard';
        header('Location: ' . $redirectUrl);
        exit;
    }
}
