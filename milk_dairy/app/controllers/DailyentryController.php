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
            if ($result === false) {
                // Handle insert error (optional: set a flash message or log error)
                header("Location: /dailyentry/index?error=insert_failed");
                exit;
            }
        }
        // Adjust the redirect path as needed for your routing setup
        header("Location: /dailyentry/index");
        exit;
    }
  public function delete($id)
    {
        $dailyentryModel = $this->model('DailyEntry');
        $dailyentryModel->delete($id);
        header("Location: ". $_SERVER['HTTP_REFERER']."");
    }


}