<?php
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check

        // Example: Fetch vendor count from your model (replace with actual logic)
        $vendorModel = $this->model('Vendor');
        $vendorCount = $vendorModel->getVendorCount();

        $data = [
            'vendor_count' => $vendorCount
        ];

        $this->view('dashboard/index', $data);
    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }
}
