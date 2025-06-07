<?php
class TestDatabase
{
	private static $pdo;

	public static function init()
	{
		self::$pdo = self::getPDO();

		// Disable foreign key checks temporarily
		self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0");

		// Truncate all tables in proper order
		$tables = [
			'order_items',
			'orders',
			'cart',
			'books',
			'categories',
			'users'
		];

		foreach ($tables as $table) {
			self::$pdo->exec("TRUNCATE TABLE $table");
		}

		// Re-enable foreign key checks
		self::$pdo->exec("SET FOREIGN_KEY_CHECKS=1");

		// Create test data
		self::createTestData();
	}

	private static function createTestData()
	{
		// Insert admin user
		$adminPass = password_hash('admin123', PASSWORD_BCRYPT);
		self::$pdo->exec("
            INSERT INTO users (email, password_hash, first_name, last_name, is_admin)
            VALUES ('admin@test.com', '$adminPass', 'Admin', 'User', 1)
        ");

		// Insert categories
		self::$pdo->exec("
            INSERT INTO categories (name) VALUES 
            ('Fiction'), ('Non-Fiction'), ('Science'), ('Technology')
        ");

		// Insert sample books
		self::$pdo->exec("
            INSERT INTO books (title, author, description, price, stock_quantity, category_id)
            VALUES
            ('Clean Code', 'Robert Martin', 'Software craftsmanship', 35.99, 10, 4),
            ('The Phoenix Project', 'Gene Kim', 'DevOps novel', 24.99, 5, 1)
        ");
	}

	public static function getPDO()
	{
		if (!self::$pdo) {
			self::$pdo = new PDO(
				"mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME'),
				getenv('DB_USER'),
				getenv('DB_PASS'),
				[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				]
			);
		}
		return self::$pdo;
	}

	public static function reset()
	{
		self::init();
	}
}
