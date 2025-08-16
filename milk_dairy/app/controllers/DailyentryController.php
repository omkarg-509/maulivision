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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $dailyentryModel = $this->model('DailyEntry');
            $result = $dailyentryModel->insert($_POST);
            if ($result) {
                // Handle insert error (optional: set a flash message or log error)
                echo json_encode([
                    'status' => 'success',
                    'redirect' => BASE_URL . 'index'
                ]);
                exit;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'redirect' => BASE_URL . 'index'
                ]);
                exit;
            }
        } else {
            $this->view('dailyentry/index');
        }
    }
  public function delete($id)
    {
        $dailyentryModel = $this->model('DailyEntry');
        $dailyentryModel->delete($id);
        header("Location: ". $_SERVER['HTTP_REFERER']."");
    }


}