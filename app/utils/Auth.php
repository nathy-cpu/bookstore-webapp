<?php
require_once __DIR__ . '/../models/User.php';

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    public static function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }

    public static function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }
} 