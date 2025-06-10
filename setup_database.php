<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/utils/Database.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $db = Database::getInstance()->getConnection();

    // Drop existing tables if they exist (in correct order respecting foreign keys)
    $db->exec("DROP TABLE IF EXISTS order_items");
    $db->exec("DROP TABLE IF EXISTS cart");
    $db->exec("DROP TABLE IF EXISTS orders");
    $db->exec("DROP TABLE IF EXISTS sessions");
    $db->exec("DROP TABLE IF EXISTS books");
    $db->exec("DROP TABLE IF EXISTS categories");
    $db->exec("DROP TABLE IF EXISTS users");

    // Create categories table
    $db->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Create users table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        phone_number VARCHAR(20),
        is_admin BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Create sessions table
    $db->exec("CREATE TABLE IF NOT EXISTS sessions (
        id VARCHAR(255) PRIMARY KEY,
        user_id INT NOT NULL,
        data TEXT,
        last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create books table with category_id
    $db->exec("CREATE TABLE IF NOT EXISTS books (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        stock INT NOT NULL DEFAULT 0,
        isbn VARCHAR(13),
        category_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
    )");

    // Create cart table
    $db->exec("CREATE TABLE IF NOT EXISTS cart (
        user_id INT NOT NULL,
        book_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, book_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
    )");

    // Create orders table
    $db->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        status VARCHAR(50) NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create order_items table
    $db->exec("CREATE TABLE IF NOT EXISTS order_items (
        order_id INT NOT NULL,
        book_id INT NOT NULL,
        quantity INT NOT NULL,
        price_at_time DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (order_id, book_id),
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
    )");

    // Insert some default categories
    $categories = [
        [
            'name' => 'Fiction',
            'description' => 'Fictional literature including novels and short stories'
        ],
        [
            'name' => 'Non-Fiction',
            'description' => 'Factual books including biographies, history, and self-help'
        ],
        [
            'name' => 'Science Fiction',
            'description' => 'Books about futuristic technology, space exploration, and alternate realities'
        ],
        ['name' => 'Mystery', 'description' => 'Detective stories, crime fiction, and suspense novels'],
        ['name' => 'Romance', 'description' => 'Love stories and romantic literature'],
        ['name' => 'Technology', 'description' => 'Books about computers, programming, and technology'],
        ['name' => 'Business', 'description' => 'Books about entrepreneurship, management, and finance'],
        ['name' => 'Children', 'description' => 'Books for young readers']
    ];

    $stmt = $db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    foreach ($categories as $category) {
        $stmt->execute([$category['name'], $category['description']]);
    }

    // Create admin user if not exists
    $adminEmail = 'admin@example.com';
    $adminPassword = 'admin123'; // In production, use a secure password
    $adminPasswordHash = password_hash($adminPassword, PASSWORD_DEFAULT);

    $stmt = $db->prepare(
        "INSERT INTO users (email, password_hash, first_name, last_name, is_admin) 
        VALUES (?, ?, ?, ?, TRUE)"
    );
    $stmt->execute([$adminEmail, $adminPasswordHash, 'Admin', 'User']);

    // Insert some sample books
    $fiction = $db->query("SELECT id FROM categories WHERE name = 'Fiction'")
        ->fetch()['id'];
    $scifi = $db->query("SELECT id FROM categories WHERE name = 'Science Fiction'")
        ->fetch()['id'];
    $mystery = $db->query("SELECT id FROM categories WHERE name = 'Mystery'")
        ->fetch()['id'];

    $books = [
        [
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'description' => 'A story of decadence and excess.',
            'price' => 550.00, // ~$10
            'stock' => 50,
            'category_id' => $fiction
        ],
        [
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'description' => 'A science fiction masterpiece about a desert planet.',
            'price' => 715.00, // ~$13
            'stock' => 30,
            'category_id' => $scifi
        ],
        [
            'title' => 'The Da Vinci Code',
            'author' => 'Dan Brown',
            'description' => 'A mystery thriller about hidden symbols and secret societies.',
            'isbn' => '9780307474278',
            'price' => 660.00, // ~$12
            'stock' => 40,
            'category_id' => $mystery
        ]
    ];

    $stmt = $db->prepare(
        "INSERT INTO books (title, author, description, price, stock, category_id) 
        VALUES (?, ?, ?, ?, ?, ?)"
    );
    foreach ($books as $book) {
        $stmt->execute([
            $book['title'],
            $book['author'],
            $book['description'],
            $book['price'],
            $book['stock'],
            $book['category_id']
        ]);
    }

    echo "Database setup completed successfully!\n";
} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage() . "\n");
}
