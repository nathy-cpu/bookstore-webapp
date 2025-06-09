<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../utils/Auth.php';

class AdminController {
    private $userModel;
    private $bookModel;

    public function __construct() {
        $this->userModel = new User();
        $this->bookModel = new Book();
        // Ensure only admin users can access this controller
        if (!Auth::isAdmin()) {
            header('Location: /');
            exit;
        }
    }

    public function index() {
        $users = $this->userModel->getAll();
        $books = $this->bookModel->getAll();
        require_once __DIR__ . '/../views/admin/index.php';
    }

    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $isAdmin = isset($_POST['is_admin']) ? 1 : 0;
            $this->userModel->create($username, $password, $isAdmin);
            header('Location: /admin');
            exit;
        }
        require_once __DIR__ . '/../views/admin/add_user.php';
    }

    public function editUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $isAdmin = isset($_POST['is_admin']) ? 1 : 0;
            $this->userModel->update($id, $username, $isAdmin);
            header('Location: /admin');
            exit;
        }
        $user = $this->userModel->getById($id);
        require_once __DIR__ . '/../views/admin/edit_user.php';
    }

    public function deleteUser($id) {
        $this->userModel->delete($id);
        header('Location: /admin');
        exit;
    }

    public function addBook() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $price = $_POST['price'];
            $this->bookModel->create($title, $author, $price);
            header('Location: /admin');
            exit;
        }
        require_once __DIR__ . '/../views/admin/add_book.php';
    }

    public function editBook($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $price = $_POST['price'];
            $this->bookModel->update($id, $title, $author, $price);
            header('Location: /admin');
            exit;
        }
        $book = $this->bookModel->getById($id);
        require_once __DIR__ . '/../views/admin/edit_book.php';
    }

    public function deleteBook($id) {
        $this->bookModel->delete($id);
        header('Location: /admin');
        exit;
    }
} 