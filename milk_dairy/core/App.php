<?php

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];
    protected $namespace = 'App\\Controllers\\';

    public function __construct()
    {
        $url = $this->parseUrl();

        // Controller resolution with namespace support
        $controllerName = ucfirst($url[0] ?? 'Home') . 'Controller';
        $controllerPath = dirname(__DIR__) . '/app/controllers/' . $controllerName . '.php';

        if (isset($url[0]) && file_exists($controllerPath)) {
            require_once $controllerPath;
            $controllerClass = $this->namespace . $controllerName;
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class not found: " . $controllerClass);
            }
            $this->controller = new $controllerClass;
            unset($url[0]);
        } else {
            $controllerPath = dirname(__DIR__) . '/app/controllers/' . $this->controller . '.php';
            if (!file_exists($controllerPath)) {
                throw new Exception("Controller file not found: " . $controllerPath);
            }
            require_once $controllerPath;
            $controllerClass = $this->namespace . $this->controller;
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class not found: " . $controllerClass);
            }
            $this->controller = new $controllerClass;
        }

        // Method resolution with HTTP verb support
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
        $method = $url[1] ?? $this->method;
        $methodWithVerb = $method . ucfirst($httpMethod);

        if (isset($url[1])) {
            if (method_exists($this->controller, $methodWithVerb)) {
                $this->method = $methodWithVerb;
                unset($url[1]);
            } elseif (method_exists($this->controller, $method)) {
                $this->method = $method;
                unset($url[1]);
            } else {
                throw new Exception("Method '{$method}' not found in controller '{$controllerClass}'.");
            }
        } elseif (method_exists($this->controller, $this->method . ucfirst($httpMethod))) {
            $this->method = $this->method . ucfirst($httpMethod);
        }

        // Parameters
        $this->params = $url ? array_values($url) : [];

        // Dependency Injection (simple example)
        $reflection = new ReflectionMethod($this->controller, $this->method);
        $dependencies = [];
        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();
                $dependencies[] = new $className();
            } elseif (!empty($this->params)) {
                $dependencies[] = array_shift($this->params);
            } elseif ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
            } else {
                throw new Exception("Cannot resolve parameter '{$param->getName()}' for method '{$this->method}'.");
            }
        }

        // Middleware support (before controller method)
        if (method_exists($this->controller, 'before')) {
            $result = call_user_func([$this->controller, 'before'], $this->method, $this->params);
            if ($result === false) {
                // Middleware can stop execution
                return;
            }
        }

        // Call controller method with parameters and dependencies
        $response = call_user_func_array([$this->controller, $this->method], $dependencies);

        // Middleware support (after controller method)
        if (method_exists($this->controller, 'after')) {
            call_user_func([$this->controller, 'after'], $this->method, $response);
        }
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
