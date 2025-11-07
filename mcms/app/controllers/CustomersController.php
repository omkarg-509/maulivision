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
        
      echo $vid = $_SESSION['vendor']['id'] ?? null;
      echo $name = trim($_POST['name'] ?? '');
      echo $mobile = trim($_POST['mobile'] ?? '');
        echo $in_time = trim($_POST['in_time'] ?? '');
        echo $amount = (float)($_POST['amount'] ?? 0);
        echo $staff = trim($_POST['staff'] ?? '');
        echo $payment_method = trim($_POST['payment_method'] ?? '');

        $customersModel = $this->model('customers');
        $id=$customersModel->create($vid,$name,$mobile,$in_time,$amount,$staff,$payment_method);

            header('Location: ' . BASE_URL . 'customers/index');
            return;
      

                                                                                                                                                                                                                                                                                                                                                                                                                                            
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