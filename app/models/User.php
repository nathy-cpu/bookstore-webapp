<?php

require_once __DIR__ . '/../utils/Database.php';
require_once __DIR__ . '/../utils/Debug.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in getById: " . $e->getMessage());
            throw new Exception("A database error occurred while fetching user", 0, $e);
        }
    }

    public function getByEmail($email)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in getByEmail: " . $e->getMessage());
            throw new Exception("A database error occurred while fetching user", 0, $e);
        }
    }

    public function create($email, $password, $firstName, $lastName, $phoneNumber = null, $isAdmin = false)
    {
        try {
            // Check if email already exists
            if ($this->getByEmail($email)) {
                throw new Exception("Email already exists");
            }

            $stmt = $this->db->prepare('
                INSERT INTO users (
                    email, password_hash, first_name, last_name, 
                    phone_number, is_admin
                ) VALUES (?, ?, ?, ?, ?, ?)
            ');
            return $stmt->execute([
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $firstName,
                $lastName,
                $phoneNumber,
                $isAdmin ? 1 : 0
            ]);
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in create: " . $e->getMessage());
            throw new Exception("A database error occurred while creating user", 0, $e);
        }
    }

    public function update($id, $email, $firstName, $lastName, $phoneNumber = null, $isAdmin = false)
    {
        try {
            $stmt = $this->db->prepare('
                UPDATE users 
                SET email = ?, first_name = ?, last_name = ?, 
                    phone_number = ?, is_admin = ? 
                WHERE id = ?
            ');
            return $stmt->execute([
                $email,
                $firstName,
                $lastName,
                $phoneNumber,
                $isAdmin ? 1 : 0,
                $id
            ]);
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in update: " . $e->getMessage());
            throw new Exception("A database error occurred while updating user", 0, $e);
        }
    }

    public function updatePassword($id, $newPassword)
    {
        try {
            $stmt = $this->db->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            return $stmt->execute([
                password_hash($newPassword, PASSWORD_DEFAULT),
                $id
            ]);
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in updatePassword: " . $e->getMessage());
            throw new Exception("A database error occurred while updating password", 0, $e);
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in delete: " . $e->getMessage());
            throw new Exception("A database error occurred while deleting user", 0, $e);
        }
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Debug::logStackTrace("Database error in getAll: " . $e->getMessage());
            throw new Exception("A database error occurred while fetching users", 0, $e);
        }
    }

    public function getFullName($id)
    {
        $user = $this->getById($id);
        if ($user) {
            return $user['first_name'] . ' ' . $user['last_name'];
        }
        return null;
    }
}
