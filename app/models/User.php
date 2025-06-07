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
    /**
     * @param mixed $email
     * @param mixed $password
     * @param mixed $first_name
     * @param mixed $last_name
     * @param mixed $phone
     */
	public static function create($email, $password, $first_name, $last_name, $phone = null) {
		$pdo = getDBConnection();
		$hashed_password = password_hash($password, PASSWORD_BCRYPT);
		
		$stmt = $pdo->prepare("
			INSERT INTO users (email, password_hash, first_name, last_name, phone) 
			VALUES (:email, :password, :first_name, :last_name, :phone)
		");
		
		$stmt->execute([
			':email' => $email,
			':password' => $hashed_password,
			':first_name' => $first_name,
			':last_name' => $last_name,
			':phone' => $phone
		]);
		
		// Create a new stdClass object
		$user = new stdClass();
		$user->id = $pdo->lastInsertId();
		$user->email = $email;
		$user->first_name = $first_name;
		$user->last_name = $last_name;
		$user->phone = $phone;
		$user->is_admin = false;
		$user->created_at = date('Y-m-d H:i:s');
		
		return $user;
	}
    /**
     * @param mixed $email
     * @param mixed $password
     */
	public static function authenticate($email, $password) {
		$pdo = getDBConnection();
		$stmt = $pdo->prepare("
			SELECT 
				user_id, 
				email, 
				password_hash, 
				first_name, 
				last_name, 
				phone, 
				is_admin, 
				created_at,
				updated_at
			FROM users 
			WHERE email = :email 
			LIMIT 1
		");
		
		$stmt->execute([':email' => $email]);
		$userData = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if (!$userData || !password_verify($password, $userData['password_hash'])) {
			throw new Exception("Invalid email or password");
		}
		
		// Create a new user object with all required properties
		$user = new stdClass();
		$user->id = $userData['user_id'] ?? null;
		$user->email = $userData['email'] ?? null;
		$user->first_name = $userData['first_name'] ?? null;
		$user->last_name = $userData['last_name'] ?? null;
		$user->phone = $userData['phone'] ?? null;
		$user->is_admin = (bool)($userData['is_admin'] ?? false);
		$user->created_at = $userData['created_at'] ?? null;
		$user->updated_at = $userData['updated_at'] ?? null;
		
		return $user;
	}
    /**
     * @param array<int,mixed> $data
     */
    private function hydrate(array $data): User
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
	// Add these methods to the User class

	public static function findByEmail($email)
	{
		$instance = new self();
		$stmt = $instance->pdo->prepare("
        SELECT user_id FROM users WHERE email = :email LIMIT 1
    ");

		$stmt->execute([':email' => $email]);
		return $stmt->fetch();
	}
    /**
     * @param mixed $id
     */
	public static function findById($id) {
		$pdo = getDBConnection();
		$stmt = $pdo->prepare("
			SELECT 
				user_id,
				email, 
				first_name, 
				last_name, 
				phone, 
				is_admin, 
				created_at
			FROM users 
			WHERE user_id = :id 
			LIMIT 1
		");
		
		$stmt->execute([':id' => $id]);
		$userData = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if (!$userData) {
			throw new Exception("User not found");
		}
		
		// Convert to object with proper null checks
		$user = new stdClass();
		$user->id = $userData['user_id'] ?? null;
		$user->email = $userData['email'] ?? null;
		$user->first_name = $userData['first_name'] ?? null;
		$user->last_name = $userData['last_name'] ?? null;
		$user->phone = $userData['phone'] ?? null;
		$user->is_admin = (bool)($userData['is_admin'] ?? false);
		$user->created_at = $userData['created_at'] ?? null;
		
		return $user;
	}
}
