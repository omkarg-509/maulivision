<?php
require_once '../app/helpers/Auth.php';
class StaffController extends Controller
{
    public function index()
    {
         Auth::check(); // 🔒 Protect the staff page
        $this->view('staff/index');
    }
}
