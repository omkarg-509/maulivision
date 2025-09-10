<?php
require_once __DIR__ . '/../helpers/Auth.php';

class DashboardController extends Controller
{
 public function index()
{
    Auth::check();
    $admin = Auth::user();
    $dashboardModel = $this->model('Dashboard');
    $vendorCount = $dashboardModel->getVendorCount($admin['id']);
    $recentVendors = $dashboardModel->recentVendors($admin['id']);
    $this->view('dashboard/index', [
        'vendorCount' => $vendorCount,
        'recentVendors' => $recentVendors
    ]);
}

public function vendors()
{
    Auth::check();
    $admin = Auth::user();
    $vendorModel = $this->model('Vendor');
    $vendors = $vendorModel->listByAdmin($admin['id']);
    $this->view('dashboard/vendors', ['vendors' => $vendors]);
}


}
