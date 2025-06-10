<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Debug.php';

class AuthController {
    private $userModel;

    public function __construct() {
        Debug::logStackTrace("Constructing AuthController");
        $this->userModel = new User();
    }

    public function login() {
        Debug::logStackTrace("AuthController->login() called");
        
        // If user is already logged in, redirect to books
        if (isset($_SESSION['user_id'])) {
            Debug::logStackTrace("User already logged in, redirecting to /books");
            header('Location: /books');
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Debug::logStackTrace("Processing login POST request");
            
            try {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                
                if (empty($email) || empty($password)) {
                    $error = 'Email and password are required';
                } else {
                    Debug::logStackTrace("Attempting login for email: " . $email);
                    
                    try {
                        $user = $this->userModel->getByEmail($email);
                        if (!$user) {
                            Debug::logStackTrace("No user found with email: " . $email);
                            $error = 'Invalid email or password';
                        } else if (!password_verify($password, $user['password_hash'])) {
                            Debug::logStackTrace("Invalid password for email: " . $email);
                            $error = 'Invalid email or password';
                        } else {
                            Debug::logStackTrace("Login successful for user ID: " . $user['id']);
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['is_admin'] = $user['is_admin'];
                            header('Location: /books');
                            exit;
                        }
                    } catch (Exception $e) {
                        if ($e->getMessage() === "A database error occurred while fetching user") {
                            Debug::logStackTrace("Database error during user lookup: " . $e->getMessage());
                            $error = 'A system error occurred. Please try again later.';
                        } else {
                            throw $e;
                        }
                    }
                }
            } catch (Throwable $e) {
                Debug::logStackTrace("Unexpected error during login: " . $e->getMessage());
                $error = 'An unexpected error occurred. Please try again later.';
            }
        } else {
            Debug::logStackTrace("Displaying login form");
        }
        
        // For GET requests or failed POST requests, display the login form
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        Debug::logStackTrace("AuthController->register() called");
        
        // If user is already logged in, redirect to books
        if (isset($_SESSION['user_id'])) {
            Debug::logStackTrace("User already logged in, redirecting to /books");
            header('Location: /books');
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Debug::logStackTrace("Processing registration POST request");
            
            try {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                
                if (empty($email) || empty($password)) {
                    $error = 'Email and password are required';
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email format';
                } else {
                    try {
                        if ($this->userModel->create($email, $password)) {
                            Debug::logStackTrace("Registration successful for email: " . $email);
                            header('Location: /login');
                            exit;
                        }
                    } catch (Exception $e) {
                        if ($e->getMessage() === "Email already exists") {
                            $error = 'This email is already registered';
                        } else if ($e->getMessage() === "A database error occurred while creating user") {
                            $error = 'A system error occurred. Please try again later.';
                        } else {
                            throw $e;
                        }
                    }
                }
            } catch (Throwable $e) {
                Debug::logStackTrace("Unexpected error during registration: " . $e->getMessage());
                $error = 'An unexpected error occurred. Please try again later.';
            }
        } else {
            Debug::logStackTrace("Displaying registration form");
        }
        
        // For GET requests or failed POST requests, display the registration form
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public static function logout() {
        Debug::logStackTrace("Processing logout request");
        session_destroy();
        header('Location: /login');
        exit;
    }
} 