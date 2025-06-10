<?php
require_once __DIR__ . '/../utils/Database.php';

class Book {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM books');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByTitle($title) {
        $stmt = $this->db->prepare('SELECT * FROM books WHERE title = ?');
        $stmt->execute([$title]);
        return $stmt->fetch();
    }

    public function create($title, $author, $description, $price, $stock) {
        $stmt = $this->db->prepare('INSERT INTO books (title, author, description, price, stock) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$title, $author, $description, $price, $stock]);
    }

    public function update($id, $title, $author, $description, $price, $stock) {
        $stmt = $this->db->prepare('UPDATE books SET title = ?, author = ?, description = ?, price = ?, stock = ? WHERE id = ?');
        return $stmt->execute([$title, $author, $description, $price, $stock, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM books WHERE id = ?');
        return $stmt->execute([$id]);
    }
} 