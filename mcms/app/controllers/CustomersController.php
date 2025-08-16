<?php

require_once '../app/helpers/Auth.php';
class CustomersController extends Controller
{
    public function index(){
     Auth::check();
     $customersModel = $this->model('Customers');
     $customersModel->getAll();
     $this->view('customers/index',['customers' => $customers]);
    }
    public function history(){
        Auth::check();
        $customersModel = $this->model('Customers');
        $customers = $customersModel->getAll();

        $this->view('customers/history', ['customers' => $customers]);
    }

    

      public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $customersModel = $this->model('Customers');
            $customersModel->insert($_POST);
        }
        header("Location: /public/customers/index");
        exit;
    }
  public function delete($id)
    {
        $customerModel = $this->model('Customer');
        $customerModel->delete($id);
        header("Location: ". $_SERVER['HTTP_REFERER']."");
    }


}