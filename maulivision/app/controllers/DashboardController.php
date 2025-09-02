<?php
class DashboardController extends Controller
{
    public function index()
    {
        // Auth::check(); // ✅ session check
        $dashboardModel = $this->model('Dashboard');
        if (method_exists($dashboardModel, 'vendorCount')) {
            $vendorCount = $dashboardModel->vendorCount();
        } else {
            $vendorCount = 0; // or handle error appropriately
        }
        $this->view('dashboard/index', ['vendorCount' => $vendorCount]);
        // $this->view('dashboard/index');

    }

    public function vendors()
    {

        $this->view('dashboard/vendors');

    }
}
