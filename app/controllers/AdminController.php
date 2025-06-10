<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Debug.php';

class AdminController
{
    private $userModel;
    private $bookModel;
    private $orderModel;
    private $categoryModel;

    public function __construct()
    {
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
        $this->orderModel = new Order();
        $this->categoryModel = new Category();
    }

    public function index()
    {
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

    public function users()
    {
        $users = $this->userModel->getAll();
        View::render('admin/users/index', ['users' => $users]);
    }

    public function addUser()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $firstName = $_POST['first_name'] ?? '';
                $lastName = $_POST['last_name'] ?? '';
                $phoneNumber = $_POST['phone_number'] ?? null;
                $isAdmin = isset($_POST['is_admin']);

                // Validate required fields
                if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
                    throw new Exception('Please fill in all required fields');
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Please enter a valid email address');
                }

                // Validate password length
                if (strlen($password) < 8) {
                    throw new Exception('Password must be at least 8 characters long');
                }

                // Validate phone number format if provided
                if (!empty($phoneNumber) && !preg_match('/^[0-9+\-\s()]*$/', $phoneNumber)) {
                    throw new Exception('Please enter a valid phone number');
                }

                // Create user
                $success = $this->userModel->create(
                    $email,
                    $password,
                    $firstName,
                    $lastName,
                    $phoneNumber,
                    $isAdmin
                );

                if (!$success) {
                    throw new Exception('Failed to create user');
                }

                header('Location: /admin/users');
                exit;
            }

            View::render('admin/users/create', ['old' => $_POST]);
        } catch (Exception $e) {
            View::render('admin/users/create', [
                'error' => $e->getMessage(),
                'old' => $_POST
            ]);
        }
    }

    public function editUser($id)
    {
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
                $newPassword = $_POST['new_password'] ?? '';
                $firstName = $_POST['first_name'] ?? '';
                $lastName = $_POST['last_name'] ?? '';
                $phoneNumber = $_POST['phone_number'] ?? null;
                $isAdmin = isset($_POST['is_admin']);

                // Validate required fields
                if (empty($email) || empty($firstName) || empty($lastName)) {
                    throw new Exception('Please fill in all required fields');
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Please enter a valid email address');
                }

                // Validate phone number format if provided
                if (!empty($phoneNumber) && !preg_match('/^[0-9+\-\s()]*$/', $phoneNumber)) {
                    throw new Exception('Please enter a valid phone number');
                }

                // Update user information
                $this->userModel->update($id, $email, $firstName, $lastName, $phoneNumber, $isAdmin);

                // Update password if provided
                if (!empty($newPassword)) {
                    if (strlen($newPassword) < 8) {
                        throw new Exception('Password must be at least 8 characters long');
                    }
                    $this->userModel->updatePassword($id, $newPassword);
                }

                // Refresh user data
                $user = $this->userModel->getById($id);
                View::render('admin/users/edit', [
                    'user' => $user,
                    'success' => 'User updated successfully'
                ]);
                return;
            }

            View::render('admin/users/edit', ['user' => $user]);
        } catch (Exception $e) {
            View::render('admin/users/edit', [
                'user' => $user ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteUser($id)
    {
        Debug::logStackTrace("AdminController->deleteUser() called for ID: " . $id);
        try {
            // Prevent self-deletion
            if ($id == $_SESSION['user_id']) {
                throw new Exception('You cannot delete your own account');
            }

            if ($this->userModel->delete($id)) {
                header('Location: /admin/users');
                exit;
            } else {
                throw new Exception('Failed to delete user');
            }
        } catch (Exception $e) {
            $users = $this->userModel->getAll();
            View::render('admin/users/index', [
                'users' => $users,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function addBook()
    {
        Debug::logStackTrace("AdminController->addBook() called");
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'] ?? '';
                $author = $_POST['author'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $stock = $_POST['stock'] ?? 0;
                $categoryId = $_POST['category_id'] ?? '';

                if (empty($title) || empty($author) || empty($price) || empty($categoryId)) {
                    throw new Exception('Title, author, price, and category are required');
                }

                $this->bookModel->create($title, $author, $description, $price, $stock, $categoryId);
                Debug::logStackTrace("Book created successfully: " . $title);
                header('Location: /admin/books');
                exit;
            }

            View::render('admin/books/create', ['old' => $_POST]);
        } catch (Exception $e) {
            Debug::logStackTrace("Error adding book: " . $e->getMessage());
            View::render('admin/books/add', [
                'error' => $e->getMessage(),
                'old' => $_POST
            ]);
        }
    }

    public function editBook($id)
    {
        Debug::logStackTrace("AdminController->editBook() called for ID: " . $id);
        try {
            $book = $this->bookModel->getById($id);
            if (!$book) {
                Debug::logStackTrace("Book not found: " . $id);
                header('Location: /admin/books');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'] ?? '';
                $author = $_POST['author'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $stock = $_POST['stock'] ?? 0;
                $categoryId = $_POST['category_id'] ?? '';

                if (empty($title) || empty($author) || empty($price) || empty($categoryId)) {
                    throw new Exception('Title, author, price, and category are required');
                }

                $this->bookModel->update($id, $title, $author, $description, $price, $stock, $categoryId);
                Debug::logStackTrace("Book updated successfully: " . $id);

                // Refresh book data
                $book = $this->bookModel->getById($id);
                View::render('admin/books/edit', [
                    'book' => $book,
                    'success' => 'Book updated successfully'
                ]);
                return;
            }

            View::render('admin/books/edit', ['book' => $book]);
        } catch (Exception $e) {
            Debug::logStackTrace("Error editing book: " . $e->getMessage());
            View::render('admin/books/edit', [
                'book' => $book ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteBook($id)
    {
        Debug::logStackTrace("AdminController->deleteBook() called for ID: " . $id);
        try {
            $this->bookModel->delete($id);
            Debug::logStackTrace("Book deleted successfully: " . $id);
            header('Location: /admin/books');
            exit;
        } catch (Exception $e) {
            Debug::logStackTrace("Error deleting book: " . $e->getMessage());
            header('Location: /admin/books?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function books()
    {
        $books = $this->bookModel->getAll();
        View::render('admin/books/index', ['books' => $books]);
    }

    public function categories()
    {
        $categories = $this->categoryModel->getAll();
        View::render('admin/categories/index', ['categories' => $categories]);
    }

    public function addCategory()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';

                if (empty($name)) {
                    throw new Exception('Category name is required');
                }

                $this->categoryModel->create($name, $description);
                header('Location: /admin/categories');
                exit;
            }

            View::render('admin/categories/create', ['old' => $_POST]);
        } catch (Exception $e) {
            View::render('admin/categories/create', [
                'error' => $e->getMessage(),
                'old' => $_POST
            ]);
        }
    }

    public function editCategory($id)
    {
        try {
            $category = $this->categoryModel->getById($id);
            if (!$category) {
                header('Location: /admin/categories');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';

                if (empty($name)) {
                    throw new Exception('Category name is required');
                }

                $this->categoryModel->update($id, $name, $description);

                // Refresh category data
                $category = $this->categoryModel->getById($id);
                View::render('admin/categories/edit', [
                    'category' => $category,
                    'success' => 'Category updated successfully'
                ]);
                return;
            }

            View::render('admin/categories/edit', ['category' => $category]);
        } catch (Exception $e) {
            View::render('admin/categories/edit', [
                'category' => $category ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $this->categoryModel->delete($id);
            header('Location: /admin/categories');
            exit;
        } catch (Exception $e) {
            header('Location: /admin/categories?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function orders()
    {
        try {
            $orders = $this->orderModel->getAll();
            View::render('admin/orders/index', ['orders' => $orders]);
        } catch (Exception $e) {
            View::render('admin/orders/index', [
                'error' => $e->getMessage(),
                'orders' => []
            ]);
        }
    }

    public function viewOrder($id)
    {
        try {
            $order = $this->orderModel->getById($id);
            if (!$order) {
                header('Location: /admin/orders?error=' . urlencode('Order not found'));
                exit;
            }
            View::render('admin/orders/view', ['order' => $order]);
        } catch (Exception $e) {
            header('Location: /admin/orders?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function processOrder($id)
    {
        try {
            $this->orderModel->updateStatus($id, 'PROCESSING');
            header('Location: /admin/orders?success=' . urlencode('Order marked as processing'));
            exit;
        } catch (Exception $e) {
            header('Location: /admin/orders?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function completeOrder($id)
    {
        try {
            $this->orderModel->updateStatus($id, 'COMPLETED');
            header('Location: /admin/orders?success=' . urlencode('Order marked as completed'));
            exit;
        } catch (Exception $e) {
            header('Location: /admin/orders?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function cancelOrder($id)
    {
        try {
            $this->orderModel->updateStatus($id, 'CANCELLED');
            header('Location: /admin/orders?success=' . urlencode('Order cancelled'));
            exit;
        } catch (Exception $e) {
            header('Location: /admin/orders?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
