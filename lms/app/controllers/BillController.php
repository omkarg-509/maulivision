<?php
require_once '../app/helpers/Auth.php';

class BillController extends Controller
{
    public function create($customerId)
    {
        Auth::check();
        $customersModel = $this->model('LaundryCustomer');
        $customer = $customersModel->findById((int)$customerId);
        if (!$customer) {
            header('Location: ' . BASE_URL . 'laundry/index');
            exit;
        }
        $this->view('laundry/bill_form', ['customer' => $customer]);
    }

    public function store()
    {
        Auth::check();
        $billModel = $this->model('Bill');
        $itemModel = $this->model('BillItem');
        $billModel->ensureTables();

        $cid = (int)($_POST['customer_id'] ?? 0);
        $items = $_POST['items'] ?? [];

        // Compute total
        $total = 0.0;
        foreach ($items as $it) {
            $qty = (int)($it['quantity'] ?? 0);
            $price = (float)($it['price'] ?? 0);
            $total += $qty * $price;
        }
        $billId = $billModel->create($cid, $total);
        foreach ($items as $it) {
            $name = trim($it['name'] ?? '');
            if ($name === '') continue;
            $qty = max(1, (int)($it['quantity'] ?? 1));
            $weight = isset($it['weight']) && $it['weight'] !== '' ? (float)$it['weight'] : null;
            $price = (float)($it['price'] ?? 0);
            $itemModel->create($billId, $name, $qty, $weight, $price);
        }

        // WhatsApp placeholder (no external call) - here you would integrate Meta API
        // e.g., send order summary to $customer['phone_number']

        header('Location: ' . BASE_URL . 'bill/show/' . $billId);
        exit;
    }

    public function show($billId)
    {
        Auth::check();
        $billModel = $this->model('Bill');
        $itemModel = $this->model('BillItem');
        $bill = $billModel->find((int)$billId);
        if (!$bill) {
            header('Location: ' . BASE_URL . 'bill/list');
            exit;
        }
        $items = $itemModel->forBill((int)$billId);
        $this->view('laundry/bill_show', ['bill' => $bill, 'items' => $items]);
    }

    public function list()
    {
        Auth::check();
        $billModel = $this->model('Bill');
        $bills = $billModel->all();
        $this->view('laundry/bill_list', ['bills' => $bills]);
    }
}
