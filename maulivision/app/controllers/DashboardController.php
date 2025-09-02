<?php
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
        if (!method_exists($this, 'model')) {
            throw new Exception("Model method not found in Controller.");
        }
        $dashboardModel = $this->model('Dashboard');
        if (!method_exists($dashboardModel, 'countVendorModels')) {
            throw new Exception("countVendorModels method not found in Dashboard model.");
        }
        $vendorCount = $dashboardModel->countVendorModels();
        $this->view('dashboard/index', ['vendorCount' => $vendorCount]);

    }

    public function vendors()
    {

        $this->view('dashboard/vendors');

    }
}
