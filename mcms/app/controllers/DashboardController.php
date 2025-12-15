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
        // $countDailyEntries = $customerModel->countDailyEntry();
        $this->view('dashboard/index', [
            'dailyEarning' => $dailyEarning,
            // 'dailyentry' => $countDailyEntries
        ]);
    }
}
