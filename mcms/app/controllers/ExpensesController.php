<?php

require_once '../app/helpers/Auth.php';
class ExpensesController extends Controller
{
   
 public function index()
    {
        Auth::check();
        $this->view('expenses/index');
    }

    public function history(){
        auth::check();
        $this->view('expenses/history');
    }
}
