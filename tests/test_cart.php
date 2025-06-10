<?php

require_once __DIR__ . '/app/utils/Router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/BookController.php';
require_once __DIR__ . '/app/controllers/CartController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/models/Book.php';
require_once __DIR__ . '/app/models/Cart.php';

$router = new Router();
$router->addRoute('GET', '/', [new HomeController(), 'index']);
$router->addRoute('GET', '/books', [new BookController(), 'index']);
$router->addRoute('GET', '/books/1', [new BookController(), 'show']);
$router->addRoute('GET', '/books/title/Test Book', [new BookController(), 'showByTitle']);
$router->addRoute('GET', '/cart', [new CartController(), 'index']);
$router->addRoute('POST', '/cart/add/1', [new CartController(), 'add']);
$router->addRoute('POST', '/cart/remove/1', [new CartController(), 'remove']);

// Simulate viewing the cart
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/cart';
$router->dispatch();

// Simulate adding an item to the cart
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/cart/add/1';
$router->dispatch();

// Simulate removing an item from the cart
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/cart/remove/1';
$router->dispatch();
