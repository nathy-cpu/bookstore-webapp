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
} 