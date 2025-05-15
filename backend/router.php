<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/utils/helper.php';
require_once __DIR__ . '/utils/auth.php';

// Define the base API path
define('BASE_PATH', '/api');

// Get the request URI and method
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove base path from request URI
$route = substr($request_uri, strlen(BASE_PATH));

// Route definitions
$routes = [
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

// Find matching route
$matched_route = null;
$params = [];

foreach ($routes as $pattern => $handler) {
	list($method, $path) = explode(' ', $pattern, 2);

	// Convert route pattern to regex
	$regex = str_replace('/', '\/', $path);
	$regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $regex);
	$regex = '/^' . $regex . '$/';

	// Check if method and path match
	if ($request_method === $method && preg_match($regex, $route, $matches)) {
		$matched_route = $handler;

		// Extract named parameters
		foreach ($matches as $key => $value) {
			if (is_string($key)) {
				$params[$key] = $value;
			}
		}

		break;
	}
}

// Handle the request
if ($matched_route) {
	try {
		// Include the controller file
		list($controller, $action) = $matched_route;
		$controller_file = __DIR__ . '/controllers/' . $controller . '.php';

		if (file_exists($controller_file)) {
			require_once $controller_file;

			// Check for session timeout on authenticated routes
			if ($controller !== 'AuthController' || $action !== 'register' && $action !== 'login') {
				checkSessionTimeout();
			}

			// Call the controller action
			if (method_exists($controller, $action)) {
				// Pass parameters to the method
				call_user_func([$controller, $action], $params);
			} else {
				jsonResponse(['error' => 'Action not found'], 404);
			}
		} else {
			jsonResponse(['error' => 'Controller not found'], 404);
		}
	} catch (Exception $e) {
		jsonResponse(['error' => $e->getMessage()], 500);
	}
} else {
	jsonResponse(['error' => 'Route not found'], 404);
}
