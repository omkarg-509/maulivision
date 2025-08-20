<?php
require_once '../app/helpers/Auth.php';
class HomeController extends Controller
{
    public function index()
    {
        Auth::check(); // 🔒 Protect the home page

        addin
   

        $this->view('dashboard/index');
    }
}
