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
        // if (isset($_COOKIE['supperadmin']) && (session_status() === PHP_SESSION_NONE || !isset($_SESSION['supperadmin']))) {
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
            $supperadmin = $userModel->findByEmailOrNumber($email_or_number);

            // Use password_verify if passwords are hashed
            // if ($supperadmin && password_verify($password, $supperadmin['password'])) {
            if ($supperadmin && $password === $supperadmin['password']) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['supperadmin'] = $supperadmin;
                setcookie("supperadmin", $supperadmin['id'], time() + (7 * 24 * 60 * 60), "/");

                echo json_encode([
                    'status' => 'success',
                    'redirect' => BASE_URL
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
        if (isset($_SESSION['supperadmin']) && !empty($_SESSION['supperadmin'])) {
            $this->redirectToDashboard();
            return true;
        }

        // Check cookie if session doesn't exist
        if (isset($_COOKIE['supperadmin']) && !empty($_COOKIE['supperadmin'])) {
            // Validate cookie by fetching user from database
            $userModel = $this->model('User');
            $supperadmin = $userModel->findById($_COOKIE['supperadmin']);
            
            if ($supperadmin) {
                // Restore session from cookie
                $_SESSION['supperadmin'] = $supperadmin;
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
    $redirectUrl = defined('BASE_URL') ? BASE_URL : '/';
    header('Location: ' . $redirectUrl);
        exit;
    }
}
