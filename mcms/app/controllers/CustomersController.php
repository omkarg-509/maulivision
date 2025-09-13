<?php

require_once '../app/helpers/Auth.php';

class CustomersController extends Controller
{
    public function index()
    {
        Auth::check();  // ✅ session check
        $customersModel = $this->model('Customers');
        $customers = $customersModel->getAll();
        $this->view('customers/index', ['customers' => $customers]);
    }

    public function history()
    {
        Auth::check();
        $customersModel = $this->model('Customers');
        $customers = $customersModel->getAll();
        $this->view('customers/history', ['customers' => $customers]);
    }

    public function store()
    {
        // Ajax-friendly store
        $customersModel = $this->model('Customers');
        try {
            $id = $customersModel->insert($_POST);
            // If request expects JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        'id' => $id,
                        'name' => $_POST['name'] ?? '',
                        'mobile' => $_POST['mobile'] ?? '',
                        'in_time' => $_POST['in_time'] ?? '',
                        'amount' => $_POST['amount'] ?? '',
                        'staff' => $_POST['staff'] ?? '',
                        'payment_method' => $_POST['payment_method'] ?? ''
                    ]
                ]);
                return;
            }
            // Fallback to redirect on non-AJAX
            header('Location: ' . BASE_URL . 'customers/index');
            exit;
        } catch (Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to save.']);
                return;
            }
            header('Location: ' . BASE_URL . 'customers/index');
            exit;
        }                                                                                                                                                                                                                                                                                                                                                                                                                                           
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