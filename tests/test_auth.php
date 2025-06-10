<?php

session_start();
require_once __DIR__ . '/app/utils/Database.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/utils/Auth.php';

$userModel = new User();

// Register a new user
$email = 'test@example.com';
$password = 'testpassword';
$first_name = 'Test';
$last_name = 'User';
if ($userModel->create($email, $password, $first_name, $last_name)) {
    echo "User registered successfully.\n";
} else {
    echo "User registration failed.\n";
}

// Log in with the registered user
$user = $userModel->getByEmail($email);
if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['user_id'];
    echo "User logged in successfully.\n";
} else {
    echo "User login failed.\n";
}

// Access a protected route
if (Auth::isLoggedIn()) {
    echo "Accessing protected route: User is logged in.\n";
} else {
    echo "Accessing protected route: User is not logged in.\n";
}
