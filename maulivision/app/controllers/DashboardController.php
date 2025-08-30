<?php
class DashboardController extends Controller
{
    public function index()
    {

        $this->view('dashboard/index');
    
    }
    public function vendors()
    {

        $this->view('dashboard/vendors');

    }
}
