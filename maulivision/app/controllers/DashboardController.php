<?php
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        require_once '../app/models/Dashboard.php';
        $dashboardModel = new Dashboard();
        $vendorCount = $dashboardModel->countVendor();
        $this->view('dashboard/index', ['vendorCount' => $vendorCount]);
        // $this->view('dashboard/index');
    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }
}
