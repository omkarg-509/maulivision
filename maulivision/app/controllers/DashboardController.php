<?php
class HomeController extends Controller
{

    public function dashboard()
    {
        $this->view('dashboard/index');
    }
}
