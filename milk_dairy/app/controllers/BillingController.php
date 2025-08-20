<?php
require_once '../app/models/Billing.php';
class BillingController extends Controller
{
    public function index()
    {
        $this->view('billing/index');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer = isset($_POST['customer']) ? trim($_POST['customer']) : '';
            $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
            $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            if (empty($customer) || $amount <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Customer and valid amount are required.']);
                exit;
            }
            $billingModel = new Billing();
            $result = $billingModel->create([
                'customer' => $customer,
                'amount' => $amount,
                'date' => $date,
                'description' => $description
            ]);
            if ($result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create bill.']);
            }
            exit;
        }
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
        exit;
    }
}
