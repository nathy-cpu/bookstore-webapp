<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/View.php';

class BookController
{
    private $bookModel;
    private $categoryModel;

    public function __construct()
    {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $categoryId = $_GET['category'] ?? null;
        $searchQuery = $_GET['search'] ?? null;

        if ($searchQuery) {
            $books = $this->bookModel->search($searchQuery, $categoryId);
        } else {
            $books = $this->bookModel->getAll($categoryId);
        }

        $categories = $this->categoryModel->getAll();
        $selectedCategory = $categoryId ? $this->categoryModel->getById($categoryId) : null;

        View::render('books/index', [
            'books' => $books,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'searchQuery' => $searchQuery
        ]);
    }

    public function show($id)
    {
        $book = $this->bookModel->getById($id);
        if (!$book) {
            header('Location: /books');
            exit;
        }
        View::render('books/show', ['book' => $book]);
    }

    public function showByTitle($title)
    {
        $book = $this->bookModel->getByTitle($title);
        if (!$book) {
            header('Location: /books');
            exit;
        }
        View::render('books/show', ['book' => $book]);
    }
}
