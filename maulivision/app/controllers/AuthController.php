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
        if (isset($_COOKIE['admin']) && (session_status() === PHP_SESSION_NONE || !isset($_SESSION['admin']))) {
            Auth::logout();
            exit;
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // If already logged in via session/cookie, return JSON success instead of redirect (avoid AJAX parse errors)
            if ($this->isUserLoggedIn()) {
                echo json_encode([
                    'status' => 'success',
                    'redirect' => defined('BASE_URL') ? BASE_URL : '/'
                ]);
                exit;
            }

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
            $admin = $userModel->findByEmailOrNumber($email_or_number);

            // Use password_verify if passwords are hashed
            if ($admin && password_verify($password, $admin['password'])) {
            // if ($admin && $password === $admin['password']) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['admin'] = $admin;
                setcookie("admin", $admin['id'], time() + (7 * 24 * 60 * 60), "/");

                echo json_encode([
                    'status' => 'success',
                    'redirect' => (defined('BASE_URL') ? BASE_URL : '/') . 'dashboard'
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
            // GET request: if already logged in, redirect to dashboard; otherwise show login view
            if ($this->isUserLoggedIn()) {
                $this->redirectToDashboard();
            }
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

        // If session is set, user is logged in
        if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
            return true;
        }

        // Check cookie if session doesn't exist and hydrate session; do NOT redirect here
        if (isset($_COOKIE['admin']) && !empty($_COOKIE['admin'])) {
            $userModel = $this->model('User');
            $admin = $userModel->findById($_COOKIE['admin']);
            if ($admin) {
                $_SESSION['admin'] = $admin;
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
    $redirectUrl = defined('BASE_URL') ? BASE_URL . 'dashboard' : '/dashboard';
    header('Location: ' . $redirectUrl);
        exit;
    }
}
