<?php

require_once '../app/helpers/Auth.php';

class CustomersController extends Controller
{
    public function index()
    {
    Auth::check();  // ✅ session check
    $customersModel = $this->model('Customers');
    $vid = $_SESSION['vendor']['id'] ?? null;
    $customers = $vid ? $customersModel->getByVendor((int)$vid) : [];
    $this->view('customers/index', ['customers' => $customers]);
    }

    public function history()
    {
        Auth::check();
    $customersModel = $this->model('Customers');
    $vid = $_SESSION['vendor']['id'] ?? null;
    $customers = $vid ? $customersModel->getByVendor((int)$vid) : [];
    $this->view('customers/history', ['customers' => $customers]);
    }
    public function customers(){
        Auth::check();  // ✅ session check
    $customersModel = $this->model('Customers');
    $vid = $_SESSION['vendor']['id'] ?? null;
    $customers = $vid ? $customersModel->getByVendorUniqueMobile((int)$vid) : [];
    $this->view('customers/customers', ['customers' => $customers]);
    }

    public function store()
    {
        // Ensure session and auth so $_SESSION['vendor'] is available
        Auth::check();

        $customersModel = $this->model('Customers');

        // Normalize payload and vendor scoping
        $payload = [
            'name' => $_POST['name'] ?? '',
            'mobile' => $_POST['mobile'] ?? '',
            'in_time' => $_POST['in_time'] ?? '',
            'amount' => isset($_POST['amount']) ? (float)$_POST['amount'] : 0,
            'staff' => $_POST['staff'] ?? '',
            'payment_method' => $_POST['payment_method'] ?? ''
        ];

        if (isset($_SESSION['vendor']['id'])) {
            $payload['vid'] = (int)$_SESSION['vendor']['id'];
        } else {
            // No vendor in session — cannot save
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
                return;
            }
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        // Basic required fields validation
        if ($payload['name'] === '' || $payload['in_time'] === '' || $payload['staff'] === '') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
                return;
            }
            header('Location: ' . BASE_URL . 'customers/index');
            exit;
        }

        // Attempt insert
        $id = 0;
        try {
            $id = (int)$customersModel->insert($payload);
        } catch (\Throwable $e) {
            $id = 0;
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjax) {
            header('Content-Type: application/json');
            if ($id > 0) {
                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        'id' => $id,
                        'name' => $payload['name'] !== '' ? $payload['name'] : 'Guest',
                        'mobile' => $payload['mobile'],
                        'in_time' => $payload['in_time'],
                        'amount' => (string)$payload['amount'],
                        'staff' => $payload['staff'],
                        'payment_method' => $payload['payment_method']
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to save']);
            }
            return;
        }

        // Non-AJAX fallback
        header('Location: ' . BASE_URL . 'customers/index');
        exit;                                                                                                                                                                                                                                                                                                                                                                                                                                            
    }

    public function delete($id)
    {
        $customersModel = $this->model('Customers');
        $deleted = $customersModel->delete($id);
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => $deleted ? 'success' : 'error']);
            return;
        }
        $back = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'customers/index');
        header("Location: " . $back);
        exit;
    }
}