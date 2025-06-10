<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../utils/Debug.php';
require_once __DIR__ . '/../utils/View.php';

class HomeController
{
    private $bookModel;

    public function __construct()
    {
        Debug::logStackTrace("Constructing HomeController");
        $this->bookModel = new Book();
    }

    public function index()
    {
        Debug::logStackTrace("HomeController->index() called");
        try {
            ob_start();
            require_once __DIR__ . '/../views/home.php';
            $content = ob_get_clean();
            require_once __DIR__ . '/../views/layouts/base.php';
        } catch (Exception $e) {
            Debug::logStackTrace("Error in home index: " . $e->getMessage());
            header("HTTP/1.1 500 Internal Server Error");
            echo "500 Internal Server Error";
        }
    }
}
