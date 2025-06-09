<?php

class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $handler = $route['handler'];
                $controller = $handler[0];
                $action = $handler[1];
                $controller->$action();
                return;
            }
        }

        // Handle 404
        header('HTTP/1.0 404 Not Found');
        echo '404 Not Found';
    }
} 