<?php
require_once '../app/helpers/Auth.php';
class StaffController extends Controller
{
    public function index()
    {
         Auth::check(); // 🔒 Protect the staff page
        $this->view('staff/index');
    }

   public function create()
   {
       Auth::check(); // 🔒 Protect the staff page
       $this->view('staff/create');
   }

  public function store()
    {
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'number' => $_POST['number'] ?? '',
                'address' => $_POST['address'] ?? '',
            ];

            // Debug: Check if all required fields are present
            if (empty($data['name']) || empty($data['number']) || empty($data['address'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'All fields are required.', 'data' => $data]);
                exit;
            }

            $StaffModel = $this->model('Staff');
            $result = $StaffModel->insert($data);

            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Staff added successfully.']);
            } else {
                // Debug: Output the data and possible error info
                if (method_exists($StaffModel, 'getLastError')) {
                    $error = $StaffModel->getLastError();
                } else {
                    $error = 'Unknown error';
                }
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to add Staff.',
                    'data' => $data,
                    'error' => $error
                ]);
            }
            exit;
        }
    }

}
