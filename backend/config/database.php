<?php
require_once __DIR__ . "./bootstrap.php";

function getDBConnection()
{
    try {
        $pdo = new PDO(
            sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                getenv("DB_HOST"),
                getenv("DB_NAME")
            ),
            getenv("DB_USER"),
            getenv("DB_PASS"),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
