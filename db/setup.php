<?php
require_once __DIR__ . '/../config/database.php';

try {
	$pdo = getDBConnection();

	// Enable foreign key constraints
	$pdo->exec("SET FOREIGN_KEY_CHECKS=0");

	// Drop tables if they exist (for clean setup)
	$tables = ['users', 'categories', 'books', 'cart', 'orders', 'order_items'];
	foreach ($tables as $table) {
		$pdo->exec("DROP TABLE IF EXISTS $table");
	}

	// Create tables
	$pdo->exec("
        CREATE TABLE users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            phone VARCHAR(20),
            is_admin BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

	$pdo->exec("
        CREATE TABLE categories (
            category_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL
        )
    ");

	$pdo->exec("
        CREATE TABLE books (
            book_id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(100) NOT NULL,
            author VARCHAR(100) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            stock_quantity INT NOT NULL DEFAULT 0,
            category_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(category_id)
        )
    ");

	$pdo->exec("
        CREATE TABLE cart (
            cart_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            book_id INT NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id),
            FOREIGN KEY (book_id) REFERENCES books(book_id),
            UNIQUE KEY unique_cart_item (user_id, book_id)
        )
    ");

	$pdo->exec("
        CREATE TABLE orders (
            order_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            total_amount DECIMAL(10,2) NOT NULL,
            status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        )
    ");

	$pdo->exec("
        CREATE TABLE order_items (
            order_item_id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            book_id INT NOT NULL,
            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(order_id),
            FOREIGN KEY (book_id) REFERENCES books(book_id)
        )
    ");

	// Create indexes for performance
	$pdo->exec("CREATE INDEX idx_users_email ON users(email)");
	$pdo->exec("CREATE INDEX idx_books_title ON books(title)");
	$pdo->exec("CREATE INDEX idx_books_author ON books(author)");
	$pdo->exec("CREATE INDEX idx_cart_user ON cart(user_id)");

	// Insert sample data
	$pdo->exec("INSERT INTO categories (name) VALUES ('Fiction'), ('Non-Fiction'), ('Science'), ('Technology')");

	$pdo->exec("
        INSERT INTO books (title, author, description, price, stock_quantity, category_id) VALUES
        ('The Great Gatsby', 'F. Scott Fitzgerald', 'A story of wealth and love in the 1920s', 12.99, 50, 1),
        ('Clean Code', 'Robert C. Martin', 'A handbook of agile software craftsmanship', 35.99, 30, 4),
        ('Sapiens', 'Yuval Noah Harari', 'A brief history of humankind', 18.50, 40, 2),
        ('The Martian', 'Andy Weir', 'An astronaut stranded on Mars', 14.95, 25, 1)
    ");

	// Create an admin user
	$adminPassword = password_hash('Admin123!', PASSWORD_BCRYPT);
	$pdo->exec("
        INSERT INTO users (email, password_hash, first_name, last_name, is_admin) VALUES
        ('admin@bookstore.com', '$adminPassword', 'Admin', 'User', TRUE)
    ");

	echo "Database setup completed successfully!\n";
} catch (PDOException $e) {
	die("Database setup failed: " . $e->getMessage());
}
