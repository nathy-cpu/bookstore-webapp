<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../php_errors.log');

// Enable debug mode
$_ENV['DEBUG'] = true;

// Clear log file at the start of the server
if (php_sapi_name() === 'cli-server') {
    file_put_contents(__DIR__ . '/../php_errors.log', '');
}

// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set the document root
define('DOC_ROOT', dirname(__DIR__));

// Handle static files
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (strpos($uri, '/public/css/') === 0 || strpos($uri, '/public/js/') === 0) {
        $file = DOC_ROOT . $uri;
        if (file_exists($file)) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $content_type = $extension === 'css' ? 'text/css' : 'application/javascript';
            header('Content-Type: ' . $content_type);
            readfile($file);
            exit;
        }
    }
}

// Load all required files
require_once __DIR__ . '/utils/Debug.php';
require_once __DIR__ . '/utils/Router.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/BookController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/OrderController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/models/Book.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/User.php'; 