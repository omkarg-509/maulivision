<?php


require_once '../app/helpers/Auth.php';

class AuthController extends Controller
{


    public function index(){
          Auth::isLoggedIn();
        $this->view('auth/login');
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
            $business_name = isset($_POST['business_name']) ? htmlspecialchars(trim($_POST['business_name'])) : '';
            $business_number = isset($_POST['business_number']) ? htmlspecialchars(trim($_POST['business_number'])) : '';
            $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
            $mobile_number = isset($_POST['mobile_number']) ? htmlspecialchars(trim($_POST['mobile_number'])) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if (empty($name) || empty($email) || empty($password)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Name, email, and password are required. Email must be valid.'
                ]);
                exit;
            }

            $userModel = $this->model('User');
            if ($userModel->findByEmail($email)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email already exists.'
                ]);
                exit;
            }

            // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $hashedPassword = $password; // Use plain password for now

            $userId = $userModel->create([
                'name' => $name,
                'business_name' => $business_name,
                'business_number' => $business_number,
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
    Auth::isLoggedIn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json'); // JSON response

        // $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
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
    public function forgot()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
            $number = isset($_POST['number']) ? htmlspecialchars(trim($_POST['number'])) : '';
            $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            if (empty($email) || empty($number) || empty($new_password)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'All fields are required.'
                ]);
                exit;
            }
            $userModel = $this->model('User');
            $user = $userModel->findByEmailAndNumber($email, $number);
            if (!$user) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No user found with provided email and number.'
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
}
