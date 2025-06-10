<?php

require_once __DIR__ . '/../utils/Database.php';

class Book
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($categoryId = null)
    {
        if ($categoryId) {
            $stmt = $this->db->prepare('
                SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.category_id = ?
            ');
            $stmt->execute([$categoryId]);
        } else {
            $stmt = $this->db->query('
                SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id
            ');
        }
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('
            SELECT b.*, c.name as category_name 
            FROM books b 
            LEFT JOIN categories c ON b.category_id = c.id 
            WHERE b.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByTitle($title)
    {
        $stmt = $this->db->prepare('
            SELECT b.*, c.name as category_name 
            FROM books b 
            LEFT JOIN categories c ON b.category_id = c.id 
            WHERE b.title = ?
        ');
        $stmt->execute([$title]);
        return $stmt->fetch();
    }

    public function create($title, $author, $description, $price, $stock, $isbn = null, $categoryId = null)
    {
        $stmt = $this->db->prepare('
            INSERT INTO books (title, author, description, price, stock, isbn, category_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        return $stmt->execute([$title, $author, $description, $price, $stock, $isbn, $categoryId]);
    }

    public function update($id, $title, $author, $description, $price, $stock, $isbn = null, $categoryId = null)
    {
        $stmt = $this->db->prepare('
            UPDATE books 
            SET title = ?, author = ?, description = ?, price = ?, stock = ?, 
                isbn = ?, category_id = ? 
            WHERE id = ?
        ');
        return $stmt->execute([$title, $author, $description, $price, $stock, $isbn, $categoryId, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM books WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getByCategory($categoryId)
    {
        return $this->getAll($categoryId);
    }

    public function search($query, $categoryId = null)
    {
        $sql = '
            SELECT b.*, c.name as category_name 
            FROM books b 
            LEFT JOIN categories c ON b.category_id = c.id 
            WHERE (b.title LIKE ? OR b.author LIKE ? OR b.description LIKE ?)
        ';
        $params = ["%$query%", "%$query%", "%$query%"];

        if ($categoryId) {
            $sql .= ' AND b.category_id = ?';
            $params[] = $categoryId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
