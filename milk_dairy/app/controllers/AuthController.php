<?php
require_once '../app/helpers/Auth.php';

class AuthController extends Controller
{
public function login()
{
    Auth::isLoggedIn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json'); // JSON response

        $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $userModel = $this->model('User');
        $vendor = $userModel->findByEmail($email);

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

    public function login()
{
    Auth::isLoggedIn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json'); // JSON response

        $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
        $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $userModel = $this->model('User');
        $vendor = $userModel->findByEmail($email);

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
}
