<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Drop existing tables if they exist (in correct order respecting foreign keys)
$pdo->exec("DROP TABLE IF EXISTS order_items");
$pdo->exec("DROP TABLE IF EXISTS cart");
$pdo->exec("DROP TABLE IF EXISTS orders");
$pdo->exec("DROP TABLE IF EXISTS sessions");
$pdo->exec("DROP TABLE IF EXISTS books");
$pdo->exec("DROP TABLE IF EXISTS users");

// Create users table
$pdo->exec("
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        is_admin TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// Create sessions table
$pdo->exec("
    CREATE TABLE sessions (
        id VARCHAR(255) PRIMARY KEY,
        user_id INT NOT NULL,
        data TEXT,
        last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

// Create books table
$pdo->exec("
    CREATE TABLE books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        stock INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// Create orders table
$pdo->exec("
    CREATE TABLE orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

// Create order_items table
$pdo->exec("
    CREATE TABLE order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        book_id INT NOT NULL,
        quantity INT NOT NULL,
        price_at_time DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
    )
");

// Create cart table
$pdo->exec("
    CREATE TABLE cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        book_id INT NOT NULL,
        quantity INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
    )
");

// Insert test data

// Test users with proper password hashing
$users = [
    ['email' => 'admin@example.com', 'password' => 'admin123', 'is_admin' => 1],
    ['email' => 'user@example.com', 'password' => 'user123', 'is_admin' => 0],
    ['email' => 'john@example.com', 'password' => 'john123', 'is_admin' => 0]
];

$stmt = $pdo->prepare("INSERT INTO users (email, password_hash, is_admin) VALUES (?, ?, ?)");
foreach ($users as $user) {
    $stmt->execute([
        $user['email'],
        password_hash($user['password'], PASSWORD_DEFAULT),
        $user['is_admin']
    ]);
}

// Test books
$pdo->exec("
    INSERT INTO books (title, author, description, price, stock) VALUES
    ('The Great Gatsby', 'F. Scott Fitzgerald', 'A story of decadence and excess.', 9.99, 50),
    ('To Kill a Mockingbird', 'Harper Lee', 'A classic of modern American literature.', 12.99, 45),
    ('1984', 'George Orwell', 'A dystopian social science fiction novel.', 10.99, 30),
    ('Pride and Prejudice', 'Jane Austen', 'A romantic novel of manners.', 8.99, 25),
    ('The Hobbit', 'J.R.R. Tolkien', 'A fantasy novel and children''s book.', 14.99, 60)
");

// Test orders for user@example.com (user id 2)
$pdo->exec("
    INSERT INTO orders (user_id, total_amount, status) VALUES
    (2, 32.97, 'completed'),
    (2, 14.99, 'pending'),
    (2, 21.98, 'cancelled')
");

// Test order items
$pdo->exec("
    INSERT INTO order_items (order_id, book_id, quantity, price_at_time) VALUES
    (1, 1, 2, 9.99),  -- 2 copies of The Great Gatsby
    (1, 3, 1, 12.99), -- 1 copy of 1984
    (2, 5, 1, 14.99), -- 1 copy of The Hobbit
    (3, 2, 1, 12.99), -- 1 copy of To Kill a Mockingbird
    (3, 4, 1, 8.99)   -- 1 copy of Pride and Prejudice
");

// Test cart items for john@example.com (user id 3)
$pdo->exec("
    INSERT INTO cart (user_id, book_id, quantity) VALUES
    (3, 1, 1), -- The Great Gatsby
    (3, 4, 2)  -- 2 copies of Pride and Prejudice
");

echo "Database setup completed successfully!\n";
echo "\nTest accounts created:\n";
foreach ($users as $user) {
    echo "Email: {$user['email']}, Password: {$user['password']}" . 
         ($user['is_admin'] ? " (Admin)\n" : "\n");
} 