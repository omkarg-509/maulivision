<?php
require_once __DIR__ . '/../helpers/Auth.php';

class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // ✅ session check
       
        $this->view('dashboard/index');
    }

    public function vendors()
    {
    Auth::check();
    $this->view('dashboard/vendors');

    }

}
