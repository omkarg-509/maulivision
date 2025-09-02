<?php
require_once __DIR__ . '/../helpers/Auth.php';

class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
       $dashboardModel = $this->model('Dashboard');
       $superadminData = $dashboardModel->getSuperAdminCount();
        $this->view('dashboard/index', ['superadminData' => $superadminData]);
    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }

}
