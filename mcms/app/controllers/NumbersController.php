<?php
require_once '../app/helpers/Auth.php';
class ContactsController extends Controller
{
    public function index()
    {
         Auth::check(); // 🔒 Protect the home page
        $this->view('contacts/numbers');
    }
}
