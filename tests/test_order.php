<?php

require_once __DIR__ . '/app/utils/Router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/BookController.php';
require_once __DIR__ . '/app/controllers/CartController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';
require_once __DIR__ . '/app/models/Book.php';
require_once __DIR__ . '/app/models/Cart.php';
require_once __DIR__ . '/app/models/Order.php';

$router = new Router();
$router->addRoute('GET', '/', [new HomeController(), 'index']);
$router->addRoute('GET', '/books', [new BookController(), 'index']);
$router->addRoute('GET', '/books/1', [new BookController(), 'show']);
$router->addRoute('GET', '/books/title/Test Book', [new BookController(), 'showByTitle']);
$router->addRoute('GET', '/cart', [new CartController(), 'index']);
$router->addRoute('POST', '/cart/add/1', [new CartController(), 'add']);
$router->addRoute('POST', '/cart/remove/1', [new CartController(), 'remove']);
$router->addRoute('GET', '/orders', [new OrderController(), 'index']);
$router->addRoute('POST', '/orders/place', [new OrderController(), 'place']);

// Simulate viewing orders
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/orders';
$router->dispatch();

// Simulate placing a new order
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/orders/place';
$router->dispatch();
