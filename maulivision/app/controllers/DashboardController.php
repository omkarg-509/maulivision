<?php
require_once __DIR__ . '/../helpers/Auth.php';

class DashboardController extends Controller
{
 public function index()
{
    Auth::check();
    $dashboardModel = $this->model('Dashboard');
    $vendorCount = $dashboardModel->getVendorCount();
    $this->view('dashboard/index', ['vendorCount' => $vendorCount]);
}

public function vendors()
{
    Auth::check();
    $dashboardModel = $this->model('Dashboard');
    $vendors = $dashboardModel->getVendors();
    $this->view('dashboard/vendors', ['vendors' => $vendors]);
}


}
