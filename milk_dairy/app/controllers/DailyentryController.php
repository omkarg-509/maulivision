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
            $dailyentryModel->insert($_POST);
        }
        header("Location: /public/dailyentry/index");
        exit;
    }
  public function delete($id)
    {
        $dailyentryModel = $this->model('DailyEntry');
        $dailyentryModel->delete($id);
        header("Location: ". $_SERVER['HTTP_REFERER']."");
    }


}