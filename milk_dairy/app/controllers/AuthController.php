<?php
require_once '../app/helpers/Auth.php';

class AuthController extends Controller
{
    public function login()
    {
        Auth::isLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
               if (session_status() === PHP_SESSION_NONE) {
                   session_start();
               }
                $_SESSION['vendor'] = $user;

                setcookie("vendor", $user['id'], time() + (7 * 24 * 60 * 60), "/");

                header('Location: ' . BASE_URL . 'dashboard');
                exit;
            } else {
                $error = "Invalid credentials.";
                $this->view('auth/login', ['error' => $error]);
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
