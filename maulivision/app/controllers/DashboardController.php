<?php
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        $dashboardModel = $this->model('Dashboard');
        $vendors = $dashboardModel->getAllVendors();
        $vendorCount = is_array($vendors) ? count($vendors) : 0;
        $this->view('dashboard/index', ['vendorCount' => $vendorCount]);
    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }
}
