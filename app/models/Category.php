<?php
require_once __DIR__ . "/../config/database.php";

class Category
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }

    public static function getAll()
    {
        $instance = new self();
        $stmt = $instance->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll();
    }
    /**
     * @param mixed $id
     */
    public static function findById($id)
    {
        $instance = new self();
        $stmt = $instance->pdo->prepare("
            SELECT * FROM categories WHERE category_id = :id LIMIT 1
        ");

        $stmt->execute([":id" => $id]);
        $category = $stmt->fetch();

        if (!$category) {
            throw new Exception("Category not found");
        }

        return $category;
    }
    /**
     * @param mixed $name
     */
    public function create($name)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (name) VALUES (:name)
        ");

        $stmt->execute([":name" => $name]);
        return $this->findById($this->pdo->lastInsertId());
    }
}
