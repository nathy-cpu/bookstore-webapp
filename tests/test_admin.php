<?php

require_once __DIR__ . '/app/utils/Router.php';
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
$router->addRoute('GET', '/admin', [new AdminController(), 'index']);
$router->addRoute('GET', '/admin/users/add', [new AdminController(), 'addUser']);
$router->addRoute('POST', '/admin/users/add', [new AdminController(), 'addUser']);
$router->addRoute('GET', '/admin/users/edit/1', [new AdminController(), 'editUser']);
$router->addRoute('POST', '/admin/users/edit/1', [new AdminController(), 'editUser']);
$router->addRoute('POST', '/admin/users/delete/1', [new AdminController(), 'deleteUser']);
$router->addRoute('GET', '/admin/books/add', [new AdminController(), 'addBook']);
$router->addRoute('POST', '/admin/books/add', [new AdminController(), 'addBook']);
$router->addRoute('GET', '/admin/books/edit/1', [new AdminController(), 'editBook']);
$router->addRoute('POST', '/admin/books/edit/1', [new AdminController(), 'editBook']);
$router->addRoute('POST', '/admin/books/delete/1', [new AdminController(), 'deleteBook']);

// Simulate viewing the admin page
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/admin';
$router->dispatch();

// Simulate adding a user
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/admin/users/add';
$router->dispatch();

// Simulate editing a user
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/admin/users/edit/1';
$router->dispatch();

// Simulate deleting a user
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/admin/users/delete/1';
$router->dispatch();

// Simulate adding a book
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/admin/books/add';
$router->dispatch();

// Simulate editing a book
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/admin/books/edit/1';
$router->dispatch();

// Simulate deleting a book
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/admin/books/delete/1';
$router->dispatch();
