<?php
class HomeController extends Controller
{
    // Public landing page (no auth)
    public function index()
    {
        $this->home('home/index');
    }

    public function about()
    {
        $this->view('home/about');
    }
}
