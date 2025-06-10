<?php
require_once __DIR__ . '/../utils/Database.php';

class Cart {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addItem($userId, $bookId, $quantity = 1) {
        // First check if item already exists in cart
        $stmt = $this->db->prepare('SELECT quantity FROM cart WHERE user_id = ? AND book_id = ?');
        $stmt->execute([$userId, $bookId]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Update quantity if item exists
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $this->db->prepare('UPDATE cart SET quantity = ? WHERE user_id = ? AND book_id = ?');
            return $stmt->execute([$newQuantity, $userId, $bookId]);
        } else {
            // Insert new item if it doesn't exist
            $stmt = $this->db->prepare('INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)');
            return $stmt->execute([$userId, $bookId, $quantity]);
        }
    }

    public function updateQuantity($userId, $bookId, $quantity) {
        $stmt = $this->db->prepare('UPDATE cart SET quantity = ? WHERE user_id = ? AND book_id = ?');
        return $stmt->execute([$quantity, $userId, $bookId]);
    }

    public function removeItem($userId, $bookId) {
        $stmt = $this->db->prepare('DELETE FROM cart WHERE user_id = ? AND book_id = ?');
        return $stmt->execute([$userId, $bookId]);
    }

    public function clearCart($userId) {
        $stmt = $this->db->prepare('DELETE FROM cart WHERE user_id = ?');
        return $stmt->execute([$userId]);
    }

    public function getItemsWithDetails($userId) {
        $stmt = $this->db->prepare('
            SELECT c.*, b.title, b.author, b.price, b.stock 
            FROM cart c 
            JOIN books b ON c.book_id = b.id 
            WHERE c.user_id = ?
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getItems($userId) {
        $stmt = $this->db->prepare('SELECT * FROM cart WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
} 