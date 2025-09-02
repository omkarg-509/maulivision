<?php
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        $dashboardModel = $this->model('Dashboard');
        $vendorCount = (int) $dashboardModel->vendorCount();
        $this->view('dashboard/index', compact('vendorCount'));
    }

    public function vendors()
    {
        Auth::check();
        $this->view('dashboard/vendors');

    }
}
