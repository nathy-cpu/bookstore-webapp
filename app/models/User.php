<?php
require_once __DIR__ . '/../utils/Database.php';
require_once __DIR__ . '/../utils/Debug.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in getById: " . $e->getMessage());
            throw new Exception("A database error occurred while fetching user", 0, $e);
        }
    }

    public function getByEmail($email) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in getByEmail: " . $e->getMessage());
            throw new Exception("A database error occurred while fetching user", 0, $e);
        }
    }

    public function create($email, $password, $isAdmin = false) {
        try {
            // Check if email already exists
            if ($this->getByEmail($email)) {
                throw new Exception("Email already exists");
            }

            $stmt = $this->db->prepare('INSERT INTO users (email, password_hash, is_admin) VALUES (?, ?, ?)');
            return $stmt->execute([
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $isAdmin ? 1 : 0
            ]);
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in create: " . $e->getMessage());
            throw new Exception("A database error occurred while creating user", 0, $e);
        }
    }

    public function update($id, $email, $isAdmin = false) {
        try {
            $stmt = $this->db->prepare('UPDATE users SET email = ?, is_admin = ? WHERE id = ?');
            return $stmt->execute([$email, $isAdmin ? 1 : 0, $id]);
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in update: " . $e->getMessage());
            throw new Exception("A database error occurred while updating user", 0, $e);
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in delete: " . $e->getMessage());
            throw new Exception("A database error occurred while deleting user", 0, $e);
        }
    }

    public function getAll() {
        try {
            $stmt = $this->db->query('SELECT * FROM users');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in getAll: " . $e->getMessage());
            throw new Exception("A database error occurred while fetching users", 0, $e);
        }
    }
} 