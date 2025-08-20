<?php
require_once '../app/helpers/Auth.php';
class HomeController extends Controller
{
    public function index()
    {
        Auth::check(); // 🔒 Protect the home page

        // Include the subscription popup
        require_once '../views/layouts/subscription_popup.php';

        $this->view('dashboard/index');
    }
}
