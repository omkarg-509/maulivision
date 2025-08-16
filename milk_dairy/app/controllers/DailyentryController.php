<?php

require_once '../app/helpers/Auth.php';
class DailyentryController extends Controller
{
    public function index(){
        Auth::check();
        $dailyEntryModel = $this->model('DailyEntry');
        $dailyEntries = $dailyEntryModel->getAll();
      
        
        $this->view('dailyentry/index', ['dailyEntries' => $dailyEntries] );
        
    }

    public function store()
    {
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'vid' => $_POST['vid'] ?? '',
                'cid' => $_POST['cid'] ?? '',
                'milktype' => $_POST['milktype'] ?? '',
                'milkliter' => $_POST['milkliter'] ?? '',
            ];

            // Debug: Check if all required fields are present
            if (empty($data['vid']) || empty($data['cid']) || empty($data['milktype']) || empty($data['milkliter'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'All fields are required.', 'data' => $data]);
                exit;
            }

            $dailyEntryModel = $this->model('DailyEntry');
            $result = $dailyEntryModel->insert($data);

            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Entry added successfully.']);
            } else {
                // Debug: Output the data and possible error info
                if (method_exists($dailyEntryModel, 'getLastError')) {
                    $error = $dailyEntryModel->getLastError();
                } else {
                    $error = 'Unknown error';
                }
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to add entry.',
                    'data' => $data,
                    'error' => $error
                ]);
            }
            exit;
        }
    }
  public function delete($id)
    {
        $dailyentryModel = $this->model('DailyEntry');
        $dailyentryModel->delete($id);
        header("Location: ". $_SERVER['HTTP_REFERER']."");
    }


}