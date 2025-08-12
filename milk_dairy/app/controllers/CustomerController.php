<?php
require_once '../app/helpers/Auth.php';
class CustomerController extends Controller
{
    public function index()
    {
        
              Auth::check(); // ✅ session check
        $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();

        $this->view('customer/index', ['customers' => $customers]);

    }
   

    public function create()
    {
          Auth::check(); // ✅ session check
           $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();
        $this->view('customer/create',['customers' => $customers]);
    }

    public function store()
    {
        $customerModel = $this->model('Customer');
        $customerModel->insert($_POST);
        header("Location: ". $_SERVER['HTTP_REFERER']."");
    }

    public function delete($id)
    {
        $customerModel = $this->model('Customer');
        $customerModel->delete($id);
        header("Location: ". $_SERVER['HTTP_REFERER']."");
    }
public function searchCustomer()
{
    if (isset($_GET['term'])) {
        $term = trim($_GET['term']);
        if (strlen($term) < 2) {
            echo json_encode([]);
            return;
        }

        $customerModel = $this->model('Customer');
        $results = $customerModel->searchByTerm($term);

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
    
}
public function show($id)
{ Auth::check(); // ✅ session check
    $customerModel = $this->model('Customer');
    $customer = $customerModel->getById($id);

    if ($customer) {
        $this->view('customer/view', ['customer' => $customer]);
    } else {
        echo "Customer data not found.";
    }
}

public function update($id)
{
    Auth::check(); // ✅ session check
    $customerModel = $this->model('Customer');
    $customer = $customerModel->getById($id);

    if ($customer) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerModel->update($id, $_POST);
            echo "<script>alert('Data successfully updated'); window.location.href='/customer/show/{$id}';</script>";
            exit;
        } else {
            echo "<script>alert('Data successfully updated'); window.location.href='/customer/show/{$id}';</script>";
            $this->view('customer/view', ['customer' => $customer]);
        }
    } else {
        echo "Customer data not found.";
    }
}
}
