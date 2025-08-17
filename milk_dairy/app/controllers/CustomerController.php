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
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vid' => $_POST['vid'] ?? '',
                'bill_id' => $_POST['bill_id'] ?? '',
                'name' => $_POST['name'] ?? '',
                'mobile' => $_POST['mobile'] ?? '',
                'address' => $_POST['address'] ?? '',
            ];

            // Debug: Check if all required fields are present
            if (empty($data['vid']) || empty($data['bill_id']) || empty($data['name']) || empty($data['mobile']) || empty($data['address'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'All fields are required.', 'data' => $data]);
                exit;
            }

            $customerModel = $this->model('Customer');
            $result = $customerModel->insert($data);

            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'New Customers added successfully.']);
            } else {
                // Debug: Output the data and possible error info
                if (method_exists($customerModel, 'getLastError')) {
                    $error = $customerModel->getLastError();
                } else {
                    $error = 'Unknown error';
                }
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to New Customers.',
                    'data' => $data,
                    'error' => $error
                ]);
            }
            exit;
        }
    }
      public function list()
    {
        Auth::check();
        $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $customers]);
        exit;
    }

 public function delete($id)
{
    Auth::check();
    $customerModel = $this->model('Customer');
    $result = $customerModel->delete($id);

    header('Content-Type: application/json');
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete customer.']);
    }
    exit;
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

public function update($id)
{
    Auth::check();
    $customerModel = $this->model('Customer');
    $customer = $customerModel->getById($id);

    if ($customer) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($customerModel->updateData($id, $_POST)) {
                echo "<script>alert('Data successfully updated'); window.location.href='/public/customer/show/{$id}';</script>";
            } else {
                echo "<script>alert('No changes were made'); window.location.href='/public/customer/show/{$id}';</script>";
            }
            exit;
        } else {
            $this->view('customer/view', ['customer' => $customer]);
        }
    } else {
        echo "Customer data not found.";
    }
}


public function show($id)
{ Auth::check(); // ✅ session check
    $customerModel = $this->model('Customer');
    $customer = $customerModel->getById($id);

    if ($customer) {
        $vid = $_SESSION['vendor']['id']; // Get the vendor ID from the session
         $milk_entries = $customerModel->getDailyEntries($vid, $id);
        // $this->view('customer/view', ['customer' => $customer]);
        $this->view('customer/view', [
    'customer' => $customer,
    'customerId' => $id,
    'milk_entries' => $milk_entries]);
    } else {
        echo "Customer data not found.";
    }
}

// public function dailyEntries($vid, $cid)
// {
//     Auth::check();
//     $customerModel = $this->model('Customer');
//     $customer = $customerModel->getById($cid);

//     if ($customer) {
//         $milk_entries = $customerModel->getDailyEntries($vid, $cid);
//         $this->view('customer/view', [
//     'customer' => $customer,
//     'customerId' => $cid,
//     'milk_entries' => $milk_entries
// ]);
//     } else {
//         echo "Customer data not found.";
//     }
// }

}
