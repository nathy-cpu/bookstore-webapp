<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Debug.php';

class AdminController {
    private $userModel;
    private $bookModel;

    public function __construct() {
        Debug::logStackTrace("Constructing AdminController");
        
        // First check if user is logged in
        Auth::requireLogin();
        
        // Then check if user is admin
        if (!Auth::isAdmin()) {
            Debug::logStackTrace("Non-admin user attempted to access admin area");
            header('Location: /books');
            exit;
        }

        $this->userModel = new User();
        $this->bookModel = new Book();
    }

    public function index() {
        Debug::logStackTrace("AdminController->index() called");
        try {
            $users = $this->userModel->getAll();
            $books = $this->bookModel->getAll();
            require_once __DIR__ . '/../views/admin/index.php';
        } catch (Exception $e) {
            Debug::logStackTrace("Error in admin index: " . $e->getMessage());
            header("HTTP/1.1 500 Internal Server Error");
            echo "500 Internal Server Error";
        }
    }

    public function addUser() {
        Debug::logStackTrace("AdminController->addUser() called");
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

                if (empty($email) || empty($password)) {
                    $error = 'Email and password are required';
                } else {
                    $this->userModel->create($email, $password, $isAdmin);
                    Debug::logStackTrace("User created successfully: " . $email);
                    header('Location: /admin');
                    exit;
                }
            } catch (Exception $e) {
                Debug::logStackTrace("Error adding user: " . $e->getMessage());
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $error = 'This email is already registered';
                } else {
                    $error = 'An error occurred while creating the user';
                }
            }
        }

        require_once __DIR__ . '/../views/admin/add_user.php';
    }

    public function editUser($id) {
        Debug::logStackTrace("AdminController->editUser() called for ID: " . $id);
        $error = null;

        try {
            $user = $this->userModel->getById($id);
            if (!$user) {
                Debug::logStackTrace("User not found: " . $id);
                header('Location: /admin');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

                if (empty($email)) {
                    $error = 'Email is required';
                } else {
                    $this->userModel->update($id, $email, $password, $isAdmin);
                    Debug::logStackTrace("User updated successfully: " . $id);
                    header('Location: /admin');
                    exit;
                }
            }
        } catch (Exception $e) {
            Debug::logStackTrace("Error editing user: " . $e->getMessage());
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = 'This email is already registered';
            } else {
                $error = 'An error occurred while updating the user';
            }
        }

        require_once __DIR__ . '/../views/admin/edit_user.php';
    }

    public function deleteUser($id) {
        Debug::logStackTrace("AdminController->deleteUser() called for ID: " . $id);
        try {
            $this->userModel->delete($id);
            Debug::logStackTrace("User deleted successfully: " . $id);
        } catch (Exception $e) {
            Debug::logStackTrace("Error deleting user: " . $e->getMessage());
        }
        header('Location: /admin');
        exit;
    }

    public function addBook() {
        Debug::logStackTrace("AdminController->addBook() called");
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $title = $_POST['title'] ?? '';
                $author = $_POST['author'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $stock = $_POST['stock'] ?? 0;

                if (empty($title) || empty($author) || empty($price)) {
                    $error = 'Title, author, and price are required';
                } else {
                    $this->bookModel->create($title, $author, $description, $price, $stock);
                    Debug::logStackTrace("Book created successfully: " . $title);
                    header('Location: /admin');
                    exit;
                }
            } catch (Exception $e) {
                Debug::logStackTrace("Error adding book: " . $e->getMessage());
                $error = 'An error occurred while creating the book';
            }
        }

        require_once __DIR__ . '/../views/admin/add_book.php';
    }

    public function editBook($id) {
        Debug::logStackTrace("AdminController->editBook() called for ID: " . $id);
        $error = null;

        try {
            $book = $this->bookModel->getById($id);
            if (!$book) {
                Debug::logStackTrace("Book not found: " . $id);
                header('Location: /admin');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'] ?? '';
                $author = $_POST['author'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $stock = $_POST['stock'] ?? 0;

                if (empty($title) || empty($author) || empty($price)) {
                    $error = 'Title, author, and price are required';
                } else {
                    $this->bookModel->update($id, $title, $author, $description, $price, $stock);
                    Debug::logStackTrace("Book updated successfully: " . $id);
                    header('Location: /admin');
                    exit;
                }
            }
        } catch (Exception $e) {
            Debug::logStackTrace("Error editing book: " . $e->getMessage());
            $error = 'An error occurred while updating the book';
        }

        require_once __DIR__ . '/../views/admin/edit_book.php';
    }

    public function deleteBook($id) {
        Debug::logStackTrace("AdminController->deleteBook() called for ID: " . $id);
        try {
            $this->bookModel->delete($id);
            Debug::logStackTrace("Book deleted successfully: " . $id);
        } catch (Exception $e) {
            Debug::logStackTrace("Error deleting book: " . $e->getMessage());
        }
        header('Location: /admin');
        exit;
    }
} 