<?php
require_once '../app/helpers/Auth.php';

class AuthController extends Controller
{
 public function index()
    {
        Auth::isLoggedIn();
        $this->view('auth/login');
    }
public function login()
{
    Auth::isLoggedIn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json'); // JSON response
        $identifier = isset($_POST['email']) ? trim($_POST['email']) : ''; // reused input field
        $password  = isset($_POST['password']) ? $_POST['password'] : '';

        if ($identifier === '' || $password === '') {
            echo json_encode(['status' => 'error', 'message' => 'Missing credentials.']);
            exit;
        }

        $userModel = $this->model('User');
        $vendor = null;

        // Try email first if it looks like an email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $vendor = $userModel->findByEmail($identifier);
        }
        // Try phone/mobile if not found yet and identifier is numeric-ish (allow +, -, spaces)
        if (!$vendor && preg_match('/^[+\-\s0-9]{6,}$/', $identifier)) {
            $vendor = $userModel->findByPhone($identifier);
        }
        // Finally try username (or any identifier fallback)
        if (!$vendor) {
            // Prefer explicit username lookup; fall back to generic identifier to cover edge schemas
            $vendor = $userModel->findByUsername($identifier) ?: $userModel->findByIdentifier($identifier);
        }

        // Password check (plain fallback). Replace with password_verify when hashes deployed.
        $valid = $vendor && (
            (isset($vendor['password']) && $vendor['password'] === $password)
            || (isset($vendor['password']) && password_get_info($vendor['password'])['algo'] !== 0 && password_verify($password, $vendor['password']))
        );

        if ($valid) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Basic session hardening
            session_regenerate_id(true);
            $_SESSION['vendor'] = [
                'id' => $vendor['id'],
                'email' => $vendor['email'] ?? null,
                'username' => $vendor['username'] ?? null,
                'bussiness_name' => $vendor['business_name'] ?? ($vendor['bussines_name'] ?? ($vendor['bussiness_name'] ?? ($vendor['bussines_name'] ?? ''))),
                'mobile' => $vendor['mobile'] ?? ($vendor['phone'] ?? null),
                'role' => $vendor['role'] ?? 'vendor'
            ];

            // Mark vendor active
            try {
                $userModel->setStatusById((int)$vendor['id'], 1);
            } catch (Exception $e) {}

            // Secure-ish cookie (adjust secure flag in HTTPS environments)
            setcookie('vendor', $vendor['id'], [
                'expires' => time() + 604800,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            echo json_encode([
                'status' => 'success',
                'redirect' => BASE_URL . 'dashboard'
            ]);
            exit;
        }

        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid credentials.'
        ]);
        exit;
    } else {
        $this->view('auth/login');
    }
}

   

    public function logout()
    {
        Auth::logout();
    }
}
