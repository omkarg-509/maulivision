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

    public function register()
    {
        // Check if user is already logged in and redirect to dashboard
        if ($this->isUserLoggedIn()) {
            $this->redirectToDashboard();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
            $business_name = isset($_POST['business_name']) ? htmlspecialchars(trim($_POST['business_name'])) : '';
            $business_number = isset($_POST['business_number']) ? htmlspecialchars(trim($_POST['business_number'])) : '';
            $business_address = isset($_POST['business_address']) ? htmlspecialchars(trim($_POST['business_address'])) : '';
            $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
            $mobile_number = isset($_POST['mobile_number']) ? htmlspecialchars(trim($_POST['mobile_number'])) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            if (
                empty($name) ||
                empty($business_name) ||
                empty($business_number) ||
                empty($business_address) ||
                empty($mobile_number) ||
                empty($password) ||
                !$email
            ) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Name, business name, business number, mobile number, business address, email, and password are required. Email must be valid.'
                ]);
                exit;
            }

            $userModel = $this->model('User');
            // Check for existing vendor by email or mobile number
            $existingByEmail = $userModel->findByEmail($email);
            $existingByMobile = $userModel->findByEmailOrNumber($mobile_number);
            if ($existingByEmail || $existingByMobile) {
                $errors = [];
                if ($existingByEmail) $errors['email'] = 'Email already registered.';
                if ($existingByMobile) $errors['mobile_number'] = 'Mobile number already registered.';

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vendor already registered.',
                    'errors' => $errors
                ]);
                exit;
            }

            // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $hashedPassword = $password; // Use plain password for now

            $userId = $userModel->create([
                'name' => $name,
                'business_name' => $business_name,
                'business_number' => $business_number,
                'business_address' => $business_address,
                'email' => $email,
                'mobile_number' => $mobile_number,
                'password' => $hashedPassword
            ]);

            if ($userId) {
                echo json_encode([
                    'status' => 'success',
                    'redirect' => BASE_URL . 'login'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Registration failed.'
                ]);
            }
            exit;
        } else {
            $this->view('auth/register');
        }
    }

    public function login()
    {
        // If cookie exists but session does not, logout and exit
        if (isset($_COOKIE['vendor']) && (session_status() === PHP_SESSION_NONE || !isset($_SESSION['vendor']))) {
            Auth::logout();
            exit;
        }

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

    public function forgot()
    {
      
        // Check if user is already logged in and redirect to dashboard
        if ($this->isUserLoggedIn()) {
            $this->redirectToDashboard();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $email_or_number = isset($_POST['email_or_number']) ? trim($_POST['email_or_number']) : '';
            $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            if (empty($email_or_number) || empty($new_password)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'All fields are required.'
                ]);
                exit;
            }
            $userModel = $this->model('User');
            $user = $userModel->findByEmailOrNumber($email_or_number);
            if (!$user) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No user found with provided email or mobile number.'
                ]);
                exit;
            }
            $updated = $userModel->updatePassword($user['id'], $new_password);
            if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'redirect' => BASE_URL . 'login'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update password.'
                ]);
            }
            exit;
        } else {
            $this->view('auth/forgot');
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

        // Check session first
        if (isset($_SESSION['vendor']) && !empty($_SESSION['vendor'])) {
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
