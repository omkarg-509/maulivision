<?php
class DashboardController extends Controller
{
    public function index()
    {
  require_once '../app/views/layouts/sidebar.php';
        $this->view('dashboard/index');
    }
}
