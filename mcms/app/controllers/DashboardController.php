<?php
require_once '../app/models/Customer.php';
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // If using auth
        $customerModel = new Customer();
        $dailyEarning = $customerModel->dailyEarning();
        $monthlyEarning = $customerModel->monthlyEarning();
        $yearlyEarning = $customerModel->yearlyEarning();
        $this->view('dashboard/index', [
            'dailyEarning' => $dailyEarning,
            'monthlyEarning' => $monthlyEarning,
            'yearlyEarning' => $yearlyEarning
        ]);
    }
}
