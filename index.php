<?php

require_once __DIR__ . '/app/bootstrap.php';

try {
    Debug::logStackTrace("Application start");
    Debug::logRequest();

    $router = new Router();

    // Auth routes - using closures to lazy-load controllers
    $router->addRoute('GET', '/login', function () {
        $controller = new AuthController();
        return [$controller, 'login'];
    });
    $router->addRoute('POST', '/login', function () {
        $controller = new AuthController();
        return [$controller, 'login'];
    });
    $router->addRoute('GET', '/register', function () {
        $controller = new AuthController();
        return [$controller, 'register'];
    });
    $router->addRoute('POST', '/register', function () {
        $controller = new AuthController();
        return [$controller, 'register'];
    });
    $router->addRoute('GET', '/logout', function () {
        $controller = new AuthController();
        return [$controller, 'logout'];
    });

    // Main routes
    $router->addRoute('GET', '/', function () {
        $controller = new HomeController();
        return [$controller, 'index'];
    });
    $router->addRoute('GET', '/books', function () {
        $controller = new BookController();
        return [$controller, 'index'];
    });
    $router->addRoute('GET', '/books/{id}', function () {
        $controller = new BookController();
        return [$controller, 'show'];
    });
    $router->addRoute('GET', '/books/title/{title}', function () {
        $controller = new BookController();
        return [$controller, 'showByTitle'];
    });

    // Cart routes
    $router->addRoute('GET', '/cart', function () {
        $controller = new CartController();
        return [$controller, 'index'];
    });
    $router->addRoute('POST', '/cart/add', function () {
        $controller = new CartController();
        return [$controller, 'add'];
    });
    $router->addRoute('POST', '/cart/update', function () {
        $controller = new CartController();
        return [$controller, 'update'];
    });
    $router->addRoute('POST', '/cart/remove', function () {
        $controller = new CartController();
        return [$controller, 'remove'];
    });
    $router->addRoute('POST', '/cart/clear', function () {
        $controller = new CartController();
        return [$controller, 'clear'];
    });
    $router->addRoute('GET', '/cart/count', function () {
        $controller = new CartController();
        return [$controller, 'getCartCount'];
    });

    // Order routes
    $router->addRoute('GET', '/orders', function () {
        $controller = new OrderController();
        return [$controller, 'index'];
    });
    $router->addRoute('POST', '/orders/create', function () {
        $controller = new OrderController();
        return [$controller, 'create'];
    });

    // Admin routes
    $router->addRoute('GET', '/admin', function () {
        $controller = new AdminController();
        return [$controller, 'index'];
    });
    $router->addRoute('GET', '/admin/books', function () {
        $controller = new AdminController();
        return [$controller, 'books'];
    });
    $router->addRoute('GET', '/admin/books/create', function () {
        $controller = new AdminController();
        return [$controller, 'addBook'];
    });
    $router->addRoute('POST', '/admin/books/create', function () {
        $controller = new AdminController();
        return [$controller, 'addBook'];
    });
    $router->addRoute('GET', '/admin/books/edit/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'editBook'];
    });
    $router->addRoute('POST', '/admin/books/edit/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'editBook'];
    });
    $router->addRoute('POST', '/admin/books/delete/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'deleteBook'];
    });

    $router->addRoute('GET', '/admin/users', function () {
        $controller = new AdminController();
        return [$controller, 'users'];
    });
    $router->addRoute('GET', '/admin/users/create', function () {
        $controller = new AdminController();
        return [$controller, 'addUser'];
    });
    $router->addRoute('POST', '/admin/users/create', function () {
        $controller = new AdminController();
        return [$controller, 'addUser'];
    });
    $router->addRoute('GET', '/admin/users/edit/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'editUser'];
    });
    $router->addRoute('POST', '/admin/users/edit/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'editUser'];
    });
    $router->addRoute('POST', '/admin/users/delete/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'deleteUser'];
    });

    $router->addRoute('GET', '/admin/categories', function () {
        $controller = new AdminController();
        return [$controller, 'categories'];
    });
    $router->addRoute('GET', '/admin/categories/create', function () {
        $controller = new AdminController();
        return [$controller, 'addCategory'];
    });
    $router->addRoute('POST', '/admin/categories/create', function () {
        $controller = new AdminController();
        return [$controller, 'addCategory'];
    });
    $router->addRoute('GET', '/admin/categories/edit/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'editCategory'];
    });
    $router->addRoute('POST', '/admin/categories/edit/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'editCategory'];
    });
    $router->addRoute('POST', '/admin/categories/delete/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'deleteCategory'];
    });

    $router->addRoute('GET', '/admin/orders', function () {
        $controller = new AdminController();
        return [$controller, 'orders'];
    });
    $router->addRoute('GET', '/admin/orders/{id}', function () {
        $controller = new AdminController();
        return [$controller, 'viewOrder'];
    });
    $router->addRoute('POST', '/admin/orders/{id}/process', function () {
        $controller = new AdminController();
        return [$controller, 'processOrder'];
    });
    $router->addRoute('POST', '/admin/orders/{id}/complete', function () {
        $controller = new AdminController();
        return [$controller, 'completeOrder'];
    });
    $router->addRoute('POST', '/admin/orders/{id}/cancel', function () {
        $controller = new AdminController();
        return [$controller, 'cancelOrder'];
    });

    // Redirect old /admin/users/add to /admin/users/create
    $router->addRoute('POST', '/admin/users/add', function () {
        $controller = new AdminController();
        return [$controller, 'addUser'];
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
