<?php
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        // $dashboardModel = $this->model('Dashboard');
        // $vendorCount = $dashboardModel->countVendorModels();
        // $this->view('dashboard/index', ['vendorCount' => $vendorCount]);
        $this->view('dashboard/index');

    }

    public function vendors()
    {

        $this->view('dashboard/vendors');

    }
}
