<?php
require_once __DIR__ . "/../config/database.php";

class User
{
    private $pdo;

    public $id;
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $is_admin;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }

    public static function create(
        $email,
        $password,
        $first_name,
        $last_name,
        $phone = null
    ) {
        $instance = new self();
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $instance->pdo->prepare("
            INSERT INTO users (email, password_hash, first_name, last_name, phone)
            VALUES (:email, :password, :first_name, :last_name, :phone)
        ");

        $stmt->execute([
            ":email" => $email,
            ":password" => $hashed_password,
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":phone" => $phone,
        ]);

        $instance->id = $instance->pdo->lastInsertId();
        return $instance->findById($instance->id);
    }

    public static function authenticate($email, $password)
    {
        $instance = new self();
        $stmt = $instance->pdo->prepare("
            SELECT * FROM users WHERE email = :email LIMIT 1
        ");

        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user["password_hash"])) {
            throw new Exception("Invalid email or password");
        }

        return $instance->hydrate($user);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, email, first_name, last_name, phone, is_admin, created_at, updated_at
            FROM users WHERE id = :id LIMIT 1
        ");

        $stmt->execute([":id" => $id]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("User not found");
        }

        return $this->hydrate($user);
    }

    private function hydrate(array $data)
    {
        $this->id = $data["id"];
        $this->email = $data["email"];
        $this->first_name = $data["first_name"];
        $this->last_name = $data["last_name"];
        $this->phone = $data["phone"];
        $this->is_admin = (bool) $data["is_admin"];
        $this->created_at = $data["created_at"];
        $this->updated_at = $data["updated_at"];
        return $this;
    }
}
?>
