<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../utils/Debug.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        Debug::logStackTrace("Constructing AuthController");
        $this->userModel = new User();
    }

    public function showLogin()
    {
        View::render('auth/login');
    }

    public function showRegister()
    {
        View::render('auth/register', ['old' => $_POST]);
    }

    public function login()
    {
        Debug::logStackTrace("AuthController->login() called");

        // If user is already logged in, redirect to books
        if (isset($_SESSION['user_id'])) {
            Debug::logStackTrace("User already logged in, redirecting to /books");
            header('Location: /books');
            exit;
        }

        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception('Please provide both email and password');
            }

            $user = $this->userModel->getByEmail($email);
            if (!$user || !password_verify($password, $user['password_hash'])) {
                throw new Exception('Invalid email or password');
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

            if ($user['is_admin']) {
                header('Location: /admin');
            } else {
                header('Location: /');
            }
            exit;
        } catch (Exception $e) {
            Debug::logStackTrace("Login failed: " . $e->getMessage());
            View::render('auth/login', [
                'error' => $e->getMessage(),
                'old' => ['email' => $email]
            ]);
        }
    }

    public function register()
    {
        Debug::logStackTrace("AuthController->register() called");

        // If user is already logged in, redirect to books
        if (isset($_SESSION['user_id'])) {
            Debug::logStackTrace("User already logged in, redirecting to /books");
            header('Location: /books');
            exit;
        }

        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $phoneNumber = $_POST['phone_number'] ?? null;

            // Validate required fields
            if (
                empty($email) || empty($password) || empty($confirmPassword) ||
                empty($firstName) || empty($lastName)
            ) {
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

            // Validate password confirmation
            if ($password !== $confirmPassword) {
                throw new Exception('Passwords do not match');
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
                $phoneNumber
            );

            if (!$success) {
                throw new Exception('Failed to create account');
            }

            // Get the newly created user for login
            $user = $this->userModel->getByEmail($email);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

            header('Location: /');
            exit;
        } catch (Exception $e) {
            Debug::logStackTrace("Registration failed: " . $e->getMessage());
            View::render('auth/register', [
                'error' => $e->getMessage(),
                'old' => [
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone_number' => $phoneNumber
                ]
            ]);
        }
    }

    public function logout()
    {
        Debug::logStackTrace("Processing logout request");
        session_destroy();
        header('Location: /login');
        exit;
    }
}
