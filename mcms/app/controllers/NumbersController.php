<?php
require_once '../app/helpers/Auth.php';
class NumbersController extends Controller
{
    public function index()
    {
         Auth::check(); // 🔒 Protect the home page
        $this->view('contacts/numbers');
    }
}
