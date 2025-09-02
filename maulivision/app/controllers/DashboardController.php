<?php
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    // public function index()
    // {
    //     Auth::check(); // ✅ session check
       
    //       $dashboardModel = $this->model('dashboard');
    //     $vendor = $dashboardModel->getAll();
    //     $this->view('dashboard/index', ['$vendor' => $vendor]);
    // }
      public function index()
    {
        Auth::check(); // ✅ session check
        $dashboardModel = $this->model('dashboard');
        $customers = $dashboardModel->getAll();
        $this->view('dashboard/index', ['customers' => $customers]);

    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }
}
