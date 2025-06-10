<?php

require_once __DIR__ . '/../utils/Database.php';

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query('SELECT * FROM categories ORDER BY name');
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name)
    {
        $stmt = $this->db->prepare('INSERT INTO categories (name) VALUES (?)');
        return $stmt->execute([$name]);
    }

    public function update($id, $name)
    {
        $stmt = $this->db->prepare('UPDATE categories SET name = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    public function delete($id)
    {
        // First check if there are any books in this category
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM books WHERE category_id = ?');
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            throw new Exception('Cannot delete category that contains books');
        }

        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getBookCount($id)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM books WHERE category_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
}
