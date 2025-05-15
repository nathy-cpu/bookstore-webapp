<?php
require_once __DIR__ . "/../models/Book.php";
require_once __DIR__ . "/../models/Category.php";
require_once __DIR__ . "/../utils/helpers.php";

class BookController
{
    public static function getBooks()
    {
        try {
            $search = $_GET["search"] ?? null;
            $category_id = $_GET["category_id"] ?? null;

            $books = Book::getAll($search, $category_id);
            jsonResponse($books);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    public static function getBookDetails()
    {
        try {
            $id = extractIdFromUri();
            $book = Book::findById($id);
            jsonResponse($book);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 404);
        }
    }
}
