<?php
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        
        $dashboardModel = $this->model('Dashboard');
        $superadminCount = $dashboardModel->countSuperAdmins();

        $this->view('dashboard/index', [
            'superadminCount' => $superadminCount
        ]);
    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }
}
