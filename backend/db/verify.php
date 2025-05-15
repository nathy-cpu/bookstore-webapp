<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../config/database.php';

try {
	$pdo = getDBConnection();

	$tables = ['users', 'categories', 'books', 'cart', 'orders', 'order_items'];

	foreach ($tables as $table) {
		$stmt = $pdo->query("SELECT COUNT(*) FROM $table");
		$count = $stmt->fetchColumn();
		echo "Table $table has $count records\n";
	}

	// Test admin user
	$stmt = $pdo->query("SELECT email, is_admin FROM users WHERE is_admin = TRUE");
	$admin = $stmt->fetch();
	echo "Admin user: " . $admin['email'] . "\n";

	// Test books
	$stmt = $pdo->query("SELECT COUNT(*) FROM books");
	$bookCount = $stmt->fetchColumn();
	echo "Total books: $bookCount\n";
} catch (PDOException $e) {
	die("Verification failed: " . $e->getMessage());
}
