<?php
require_once __DIR__ . '/../utils/Database.php';

class Cart {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addItem($userId, $bookId) {
        $stmt = $this->db->prepare('INSERT INTO cart (user_id, book_id) VALUES (?, ?)');
        return $stmt->execute([$userId, $bookId]);
    }

    public function removeItem($userId, $bookId) {
        $stmt = $this->db->prepare('DELETE FROM cart WHERE user_id = ? AND book_id = ?');
        return $stmt->execute([$userId, $bookId]);
    }

    public function getItems($userId) {
        $stmt = $this->db->prepare('SELECT * FROM cart WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
} 