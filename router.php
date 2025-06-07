<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/utils/helper.php';
require_once __DIR__ . '/utils/auth.php';

class Router {
    private const BASE_PATH = '/api';
    private $routes = [];
    private $requestUri;
    private $requestMethod;

    public function __construct() {
        $this->validateServerVars();
        $this->initializeRequest();
        $this->registerRoutes();
    }

    private function validateServerVars(): void {
        if (!isset($_SERVER['REQUEST_URI'])) {
            throw new RuntimeException('REQUEST_URI server variable is not set');
        }

        if (!isset($_SERVER['REQUEST_METHOD'])) {
            throw new RuntimeException('REQUEST_METHOD server variable is not set');
        }
    }

	private function initializeRequest(): void {
		// Use the actual request data
		$this->requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
		$this->requestMethod = strtoupper(
			$_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] 
			?? $_SERVER['REQUEST_METHOD'] 
			?? 'GET'
		);

		// Normalize path
		$this->requestUri = preg_replace('#^/api#', '', $this->requestUri);
		$this->requestUri = $this->requestUri ?: '/';
		
		error_log("Final routing: {$this->requestMethod} {$this->requestUri}");
	}

    private function registerRoutes(): void {
        $this->routes = [
            // Auth routes
            'POST /register' => ['AuthController', 'register'],
            'POST /login' => ['AuthController', 'login'],
            'POST /logout' => ['AuthController', 'logout'],
            'GET /profile' => ['AuthController', 'profile'],

            // Book routes
            'GET /books' => ['BookController', 'getBooks'],
            'GET /books/{id}' => ['BookController', 'getBookDetails'],

            // Cart routes
            'GET /cart' => ['CartController', 'getCart'],
            'POST /cart/add' => ['CartController', 'addToCart'],
            'POST /cart/remove' => ['CartController', 'removeFromCart'],
            'POST /checkout' => ['CartController', 'checkout'],

            // Admin routes
            'POST /admin/books' => ['AdminController', 'addBook'],
            'PUT /admin/books/{id}' => ['AdminController', 'updateBook'],
            'DELETE /admin/books/{id}' => ['AdminController', 'deleteBook'],
            'GET /admin/users' => ['AdminController', 'getUsers'],
            'DELETE /admin/users/{id}' => ['AdminController', 'deleteUser']
        ];
    }

    public function dispatch(): void {
		error_log("Trying to route: {$this->requestMethod} {$this->requestUri}");

        foreach ($this->routes as $pattern => $handler) {
			error_log("Checking route: {$pattern}");
            list($method, $path) = explode(' ', $pattern, 2);

            if ($this->requestMethod !== $method) {
                continue;
            }

            $regex = $this->convertPatternToRegex($path);
            if (preg_match($regex, $this->requestUri, $matches)) {
                $this->handleMatchedRoute($handler, $matches);
                return;
            }
        }

        jsonResponse(['error' => 'Route not found'], 404);
    }

    private function convertPatternToRegex(string $path): string {
        $regex = str_replace('/', '\/', $path);
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $regex);
        return '/^' . $regex . '$/';
    }

    private function handleMatchedRoute(array $handler, array $matches): void {
        list($controller, $action) = $handler;
        $params = $this->extractParams($matches);

        $controllerFile = __DIR__ . '/controllers/' . $controller . '.php';
        if (!file_exists($controllerFile)) {
            jsonResponse(['error' => 'Controller not found'], 404);
            return;
        }

        require_once $controllerFile;

        if (!method_exists($controller, $action)) {
            jsonResponse(['error' => 'Action not found'], 404);
            return;
        }

        $this->checkAuthentication($controller, $action);
        call_user_func([$controller, $action], $params);
    }

    private function extractParams(array $matches): array {
        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        return $params;
    }

    private function checkAuthentication(string $controller, string $action): void {
        if ($controller !== 'AuthController' || ($action !== 'register' && $action !== 'login')) {
            checkSessionTimeout();
        }
    }
}

// Execute the router
try {
    (new Router())->dispatch();
} catch (Exception $e) {
    jsonResponse(['error' => $e->getMessage()], 500);
}
