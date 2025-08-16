<?php

class App
{
    protected $controller = 'HomeController'; // Default controller
    protected $method = 'index';              // Default method
    protected $params = [];                   // Default parameters

    public function __construct()               // Constructor
    {
        $url = $this->parseUrl();               // Parse URL
                                                // Controller check
print_r($url);
        // If the first part of the URL matches a controller file, set it
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

       
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Method check
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Remaining parts = parameters
        $this->params = $url ? array_values($url) : [];

        // Final call
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return []; // default fallback
    }

}
