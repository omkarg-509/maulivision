<?php
require_once '../app/helpers/Auth.php';
class HomeController extends Controller
{
    public function index()
    {
        Auth::isLoggedIn(); // 🔒 Protect the home page
        $this->view('dashboard/index');
    }
}
