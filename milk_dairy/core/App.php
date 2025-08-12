<?php

class App
{
    protected $controller = 'HomeController'; // Default controller
    protected $method = '';              // Default method
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
// echo "<pre>";
// print_r($url); // 👈 या लाइनमुळे browser मध्ये URL array दिसेल
// echo "</pre>";
        // Controller check
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]); // ✅ हे विसरू नकोस
        }

        // Load controller file
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
