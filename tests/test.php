<?php
require_once __DIR__ . '/app/utils/Router.php';
require_once __DIR__ . '/app/controllers/HomeController.php';

$router = new Router();
$router->addRoute('GET', '/', [new HomeController(), 'index']);

// Simulate a request to the home page
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';

$router->dispatch(); 