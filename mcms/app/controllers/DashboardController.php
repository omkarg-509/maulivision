<?php
require_once '../app/models/Customers.php';
require_once '../app/helpers/Auth.php';
class DashboardController extends Controller
{
public function index()
{
    Auth::check(); // If using auth

    // $customerModel = new Customer();
    // $customerCount = $customerModel->countAll();
    // $countDailyEntries = $customerModel->countDailyEntry();
    $countersModel = $this->model('Customers');
    $dailyEarning = $countersModel->dailyEarning();

    $this->view('dashboard/index', [
        // 'customerCount' => $customerCount,
        // 'dailyentry' => $countDailyEntries
        'dailyEarning' => $dailyEarning
    ]);
}
}
