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

            $dailyEntryModel = $this->model('DailyEntry');
            $result = $dailyEntryModel->insert($data);

            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Entry added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add entry.']);
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