<?php
require_once __DIR__ . '/../helpers/Auth.php';

class DashboardController extends Controller
{
 public function index()
{
    Auth::check();
    $dashboardModel = $this->model('Dashboard');
    $superadminCount = $dashboardModel->getSuperAdminCount();
    $this->view('dashboard/index', ['superadminCount' => $superadminCount]);
}

public function vendors()
{
    Auth::check();
    $dashboardModel = $this->model('Dashboard');
    $vendors = $dashboardModel->getVendors();
    $this->view('dashboard/vendors', ['vendors' => $vendors]);
}


}
