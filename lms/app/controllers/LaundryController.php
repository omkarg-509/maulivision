<?php
require_once '../app/helpers/Auth.php';

class LaundryController extends Controller
{
    public function index()
    {
        Auth::check();
        $customersModel = $this->model('LaundryCustomer');
        $ordersModel = $this->model('LaundryOrder');
        // ensure tables
        $customersModel->ensureTable();
        $ordersModel->ensureTable();

        $customers = $customersModel->getAllWithOrderDates();
        $this->view('laundry/index', ['customers' => $customers]);
    }

    public function store()
    {
        Auth::check();
        $customersModel = $this->model('LaundryCustomer');
        $ordersModel = $this->model('LaundryOrder');
        $customersModel->ensureTable();
        $ordersModel->ensureTable();

        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone_number'] ?? '');
        $start = trim($_POST['start_date'] ?? '');
        $end = trim($_POST['end_date'] ?? '');
        if ($name === '') { $name = 'Guest'; }
        if ($start === '' || $end === '') {
            header('Location: ' . BASE_URL . 'laundry/index');
            exit;
        }
        $cid = $customersModel->insert([
            'customer_name' => $name,
            'phone_number' => $phone,
        ]);
        $oid = $ordersModel->insert([
            'customer_id' => $cid,
            'start_date' => $start,
            'end_date' => $end,
        ]);

        // Simple JSON for AJAX or redirect with receipt view
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==='xmlhttprequest'){
            header('Content-Type: application/json');
            echo json_encode(['status'=>'success','customer_id'=>$cid,'order_id'=>$oid]);
            return;
        }
        header('Location: ' . BASE_URL . 'laundry/receipt/' . $cid);
        exit;
    }

    public function receipt($customerId)
    {
        Auth::check();
        $customersModel = $this->model('LaundryCustomer');
        $ordersModel = $this->model('LaundryOrder');
        $customer = $customersModel->findById((int)$customerId);
        $order = $ordersModel->findLatestByCustomer((int)$customerId);
        $this->view('laundry/receipt', ['customer'=>$customer,'order'=>$order]);
    }
}
