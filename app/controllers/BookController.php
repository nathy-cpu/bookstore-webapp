<?php
require_once __DIR__ . '/../models/Book.php';

class BookController {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new Book();
    }

    public function index() {
        $books = $this->bookModel->getAll();
        require_once __DIR__ . '/../views/books/index.php';
    }

    public function show($id) {
        $book = $this->bookModel->getById($id);
        require_once __DIR__ . '/../views/books/show.php';
    }

    public function showByTitle($title) {
        $book = $this->bookModel->getByTitle($title);
        require_once __DIR__ . '/../views/books/show.php';
    }
} 