<?php
require_once __DIR__ . "./../config/database.php";

class Book
{
    private $pdo;

    public $id;
    public $title;
    public $author;
    public $description;
    public $price;
    public $stock_quantity;
    public $category_id;
    public $created_at;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }

    public static function getAll($search = null, $category_id = null)
    {
        $instance = new self();
        $query = "SELECT * FROM books WHERE 1=1";
        $params = [];

        if ($search) {
            $query .= " AND title LIKE :search";
            $params[":search"] = "%$search%";
        }

        if ($category_id) {
            $query .= " AND category_id = :category_id";
            $params[":category_id"] = $category_id;
        }

        $stmt = $instance->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        $instance = new self();
        $stmt = $instance->pdo->prepare("
            SELECT b.*, c.name as category_name
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.category_id
            WHERE b.book_id = :id LIMIT 1
        ");

        $stmt->execute([":id" => $id]);
        $book = $stmt->fetch();

        if (!$book) {
            throw new Exception("Book not found");
        }

        return $instance->hydrate($book);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO books (title, author, description, price, stock_quantity, category_id)
            VALUES (:title, :author, :description, :price, :stock_quantity, :category_id)
        ");

        $stmt->execute([
            ":title" => $data["title"],
            ":author" => $data["author"],
            ":description" => $data["description"],
            ":price" => $data["price"],
            ":stock_quantity" => $data["stock_quantity"],
            ":category_id" => $data["category_id"],
        ]);

        $this->id = $this->pdo->lastInsertId();
        return $this->findById($this->id);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE books SET
                title = :title,
                author = :author,
                description = :description,
                price = :price,
                stock_quantity = :stock_quantity,
                category_id = :category_id
            WHERE book_id = :id
        ");

        $stmt->execute([
            ":id" => $id,
            ":title" => $data["title"],
            ":author" => $data["author"],
            ":description" => $data["description"],
            ":price" => $data["price"],
            ":stock_quantity" => $data["stock_quantity"],
            ":category_id" => $data["category_id"],
        ]);

        return $this->findById($id);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE book_id = :id");
        return $stmt->execute([":id" => $id]);
    }

    private function hydrate(array $data): Book
    {
        $this->id = $data["book_id"];
        $this->title = $data["title"];
        $this->author = $data["author"];
        $this->description = $data["description"];
        $this->price = $data["price"];
        $this->stock_quantity = $data["stock_quantity"];
        $this->category_id = $data["category_id"];
        $this->created_at = $data["created_at"];
        return $this;
    }
}
