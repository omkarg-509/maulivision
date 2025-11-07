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
        Auth::check();
        $vid = $_SESSION['vendor']['id'] ?? null;
        if (!$vid) {
            $_SESSION['error'] = 'Vendor not found in session';
            header('Location: ' . BASE_URL . 'customers/index');
            return;
        }

        $data = [
            'vid' => (int)$vid,
            'name' => trim($_POST['name'] ?? ''),
            'mobile' => trim($_POST['mobile'] ?? ''),
            'in_time' => trim($_POST['in_time'] ?? ''),
            'amount' => (float)($_POST['amount'] ?? 0),
            'staff' => trim($_POST['staff'] ?? ''),
            'payment_method' => trim($_POST['payment_method'] ?? ''),
        ];

        // Basic validation
        if ($data['name'] === '' || $data['in_time'] === '' || $data['amount'] <= 0 || $data['staff'] === '') {
            $_SESSION['error'] = 'Please fill in all required fields correctly.';
            header('Location: ' . BASE_URL . 'customers/index');
            return;
        }

        $customersModel = $this->model('Customers');
        $customersModel->insert($data);

        header('Location: ' . BASE_URL . 'customers/index');
                                                                                                                                                                                                                                                                                                                                                                                                                                                
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