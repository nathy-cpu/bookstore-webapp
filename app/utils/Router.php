<?php
require_once __DIR__ . '/Debug.php';

class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        Debug::logStackTrace("Adding route: $method $path");
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    private function matchRoute($method, $path) {
        Debug::logStackTrace("Matching route: $method $path");
        foreach ($this->routes as $route) {
            Debug::logStackTrace("Checking against route: {$route['method']} {$route['path']}");
            
            if ($route['method'] !== $method) {
                Debug::logStackTrace("Method mismatch: {$route['method']} != $method");
                continue;
            }

            // Convert route parameters to regex pattern
            $pattern = preg_replace('/\{([^}]+)\}/', '(?P<\1>[^/]+)', $route['path']);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            Debug::logStackTrace("Testing pattern: $pattern against path: $path");
            
            if (preg_match($pattern, $path, $matches)) {
                // Remove numeric keys from matches
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                Debug::logStackTrace("Route matched with params: " . print_r($params, true));

                return [
                    'handler' => $route['handler'],
                    'params' => $params
                ];
            }
            Debug::logStackTrace("Pattern did not match");
        }
        Debug::logStackTrace("No matching route found");
        return null;
    }

    public function dispatch() {
        Debug::logRequest();
        Debug::logStackTrace("Starting dispatch");

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        Debug::logStackTrace("Dispatching request: $method $path");
        
        $match = $this->matchRoute($method, $path);
        
        if ($match) {
            $handler = $match['handler'];
            $params = $match['params'];
            
            try {
                // Handle lazy-loaded controllers
                if (is_callable($handler) && !is_array($handler)) {
                    Debug::logStackTrace("Resolving lazy-loaded controller");
                    $handler = $handler();
                }
                
                if (is_array($handler) && count($handler) === 2) {
                    $controller = $handler[0];
                    $action = $handler[1];
                    
                    Debug::logStackTrace("Calling controller: " . (is_object($controller) ? get_class($controller) : $controller) . "->$action");
                    
                    if (!empty($params)) {
                        Debug::logStackTrace("Calling with params: " . print_r($params, true));
                        call_user_func_array([$controller, $action], array_values($params));
                    } else {
                        Debug::logStackTrace("Calling without params");
                        $controller->$action();
                    }
                    Debug::logStackTrace("Controller call completed");
                } else {
                    Debug::logStackTrace("Invalid handler format");
                    throw new Exception('Invalid route handler format');
                }
            } catch (Throwable $e) {
                Debug::logStackTrace("Controller threw exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                throw $e;
            }
        } else {
            Debug::logStackTrace("No matching route found");
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
} 