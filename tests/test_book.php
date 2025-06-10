<?php
require_once __DIR__ . '/app/utils/Router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/BookController.php';
require_once __DIR__ . '/app/controllers/CartController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/models/Book.php';

$router = new Router();
$router->addRoute('GET', '/', [new HomeController(), 'index']);
$router->addRoute('GET', '/books', [new BookController(), 'index']);
$router->addRoute('GET', '/books/1', [new BookController(), 'show']);
$router->addRoute('GET', '/books/title/Test Book', [new BookController(), 'showByTitle']);
$router->addRoute('GET', '/cart', [new CartController(), 'index']);
$router->addRoute('POST', '/cart/add/1', [new CartController(), 'add']);
$router->addRoute('POST', '/cart/remove/1', [new CartController(), 'remove']);

// Simulate accessing the book listing page
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/books';
$router->dispatch();

// Simulate viewing a specific book
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/books/1';
$router->dispatch();

// Simulate fetching a book by title
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/books/title/Test Book';
$router->dispatch();

$bookModel = new Book();
$book = $bookModel->getByTitle('Some Book Title'); 