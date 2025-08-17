<?php
// In your router logic
if ($url[0] == 'subscription') {
    $controller = new SubscriptionController();
    $controller->index();
}

class SubscriptionController extends Controller
{
    public function index()
    {
        $this->view('subscription/index');
    }
}