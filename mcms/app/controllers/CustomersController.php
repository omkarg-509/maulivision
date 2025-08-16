<?php

require_once '../app/helpers/Auth.php';

class CustomersController extends Controller
{
    public function index()
    {
        Auth::check();
        $customersModel = $this->model('Customers');
        $customers = $customersModel->getAll();
        $this->view('customers/index', ['customers' => $customers]);
    }

    public function history()
    {
        Auth::check();
        $customersModel = $this->model('Customers');
        $customers = $customersModel->getAll();
        $this->view('customers/history', ['customers' => $customers]);
    }

    public function store()
    {
        $customersModel = $this->model('Customers');
        $customersModel->insert($_POST);
        $customers = $customersModel->getAll();
        $this->view('customers/index', ['customers' => $customers]);
    }

    public function delete($id)
    {
        $customersModel = $this->model('Customers');
        $customersModel->delete($id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}