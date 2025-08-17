<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// In your router logic
if ($url[0] == 'subscription') {
    $controller = new SubscriptionController();
    $controller->index();
}
require_once '../config/config.php';  // Load configuration
require_once '../core/App.php';   // Load application core
require_once '../core/Controller.php';  // Load base controller

$app = new App(); 

