<?php
require_once __DIR__ . '/../utils/Database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $bookId) {
        $stmt = $this->db->prepare('INSERT INTO orders (user_id, book_id) VALUES (?, ?)');
        return $stmt->execute([$userId, $bookId]);
    }

    public function getByUserId($userId) {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
} 