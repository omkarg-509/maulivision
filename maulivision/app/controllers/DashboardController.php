<?php
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        $dashboardModel = $this->model('Dashboard');
        $vendorCount = method_exists($dashboardModel, 'vendorCount')
            ? (int) $dashboardModel->vendorCount()
            : 0;

        $this->view('dashboard/index', compact('vendorCount'));

    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }
}
