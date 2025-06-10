<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

// Enable debug mode
$_ENV['DEBUG'] = true;

// Clear log file at the start of the server
if (php_sapi_name() === 'cli-server') {
    file_put_contents(__DIR__ . '/php_errors.log', '');
}

// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/app/utils/Debug.php';
require_once __DIR__ . '/app/utils/Router.php';

// Load all required files
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/BookController.php';
require_once __DIR__ . '/app/controllers/CartController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';
require_once __DIR__ . '/app/models/Book.php';
require_once __DIR__ . '/app/models/Cart.php';
require_once __DIR__ . '/app/models/Order.php';
require_once __DIR__ . '/app/models/User.php';

// Set the document root
define('DOC_ROOT', __DIR__);

// Handle static files
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (strpos($uri, '/css/') === 0 || strpos($uri, '/js/') === 0) {
    $file = DOC_ROOT . '/public' . $uri;
    if (file_exists($file)) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $content_type = $extension === 'css' ? 'text/css' : 'application/javascript';
        header('Content-Type: ' . $content_type);
        readfile($file);
        exit;
    }
}

try {
    Debug::logStackTrace("Application start");
    Debug::logRequest();
    
    $router = new Router();

    // Auth routes - using closures to lazy-load controllers
    $router->addRoute('GET', '/login', function() { 
        $controller = new AuthController(); 
        return [$controller, 'login']; 
    });
    $router->addRoute('POST', '/login', function() { 
        $controller = new AuthController(); 
        return [$controller, 'login']; 
    });
    $router->addRoute('GET', '/register', function() { 
        $controller = new AuthController(); 
        return [$controller, 'register']; 
    });
    $router->addRoute('POST', '/register', function() { 
        $controller = new AuthController(); 
        return [$controller, 'register']; 
    });
    $router->addRoute('GET', '/logout', [AuthController::class, 'logout']);

    // Main routes
    $router->addRoute('GET', '/', function() { 
        $controller = new HomeController(); 
        return [$controller, 'index']; 
    });
    $router->addRoute('GET', '/books', function() { 
        $controller = new BookController(); 
        return [$controller, 'index']; 
    });
    $router->addRoute('GET', '/books/{id}', function() { 
        $controller = new BookController(); 
        return [$controller, 'show']; 
    });
    $router->addRoute('GET', '/books/title/{title}', function() { 
        $controller = new BookController(); 
        return [$controller, 'showByTitle']; 
    });

    // Cart routes
    $router->addRoute('GET', '/cart', function() { 
        $controller = new CartController(); 
        return [$controller, 'index']; 
    });
    $router->addRoute('POST', '/cart/add', function() { 
        $controller = new CartController(); 
        return [$controller, 'add']; 
    });
    $router->addRoute('POST', '/cart/update', function() { 
        $controller = new CartController(); 
        return [$controller, 'update']; 
    });
    $router->addRoute('POST', '/cart/remove', function() { 
        $controller = new CartController(); 
        return [$controller, 'remove']; 
    });
    $router->addRoute('POST', '/cart/clear', function() { 
        $controller = new CartController(); 
        return [$controller, 'clear']; 
    });

    // Order routes
    $router->addRoute('GET', '/orders', function() { 
        $controller = new OrderController(); 
        return [$controller, 'index']; 
    });
    $router->addRoute('POST', '/orders/create', function() { 
        $controller = new OrderController(); 
        return [$controller, 'create']; 
    });

    // Admin routes
    $router->addRoute('GET', '/admin', function() { 
        $controller = new AdminController(); 
        return [$controller, 'index']; 
    });
    $router->addRoute('GET', '/admin/users/add', function() { 
        $controller = new AdminController(); 
        return [$controller, 'addUser']; 
    });
    $router->addRoute('POST', '/admin/users/add', function() { 
        $controller = new AdminController(); 
        return [$controller, 'addUser']; 
    });
    $router->addRoute('GET', '/admin/users/edit/{id}', function() { 
        $controller = new AdminController(); 
        return [$controller, 'editUser']; 
    });
    $router->addRoute('POST', '/admin/users/edit/{id}', function() { 
        $controller = new AdminController(); 
        return [$controller, 'editUser']; 
    });
    $router->addRoute('POST', '/admin/users/delete/{id}', function() { 
        $controller = new AdminController(); 
        return [$controller, 'deleteUser']; 
    });
    $router->addRoute('GET', '/admin/books/add', function() { 
        $controller = new AdminController(); 
        return [$controller, 'addBook']; 
    });
    $router->addRoute('POST', '/admin/books/add', function() { 
        $controller = new AdminController(); 
        return [$controller, 'addBook']; 
    });
    $router->addRoute('GET', '/admin/books/edit/{id}', function() { 
        $controller = new AdminController(); 
        return [$controller, 'editBook']; 
    });
    $router->addRoute('POST', '/admin/books/edit/{id}', function() { 
        $controller = new AdminController(); 
        return [$controller, 'editBook']; 
    });
    $router->addRoute('POST', '/admin/books/delete/{id}', function() { 
        $controller = new AdminController(); 
        return [$controller, 'deleteBook']; 
    });

    Debug::logStackTrace("Routes configured, starting dispatch");
    $router->dispatch();

} catch (Throwable $e) {
    Debug::logStackTrace("Uncaught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    error_log("Uncaught exception: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    header("HTTP/1.1 500 Internal Server Error");
    echo "500 Internal Server Error";
} 