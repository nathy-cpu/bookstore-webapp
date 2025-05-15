<?php
require_once __DIR__ . "./../models/Book.php";
require_once __DIR__ . "./../models/User.php";
require_once __DIR__ . "./../models/Category.php";
require_once __DIR__ . "./../utils/auth.php";
require_once __DIR__ . "./../utils/helpers.php";

class AdminController
{
    public static function addBook()
    {
        authenticateAdmin();
        $data = getJsonInput();

        try {
            $required = ["title", "author", "price", "stock_quantity"];
            validateRequiredFields($data, $required);

            $book = new Book();
            $newBook = $book->create($data);

            jsonResponse($newBook, 201);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }

    public static function updateBook()
    {
        authenticateAdmin();
        $id = extractIdFromUri();
        $data = getJsonInput();

        try {
            $book = new Book();
            $updatedBook = $book->update($id, $data);

            jsonResponse($updatedBook);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }

    public static function deleteBook()
    {
        authenticateAdmin();
        $id = extractIdFromUri();

        try {
            $book = new Book();
            $success = $book->delete($id);

            if ($success) {
                jsonResponse(["message" => "Book deleted successfully"]);
            } else {
                jsonResponse(["error" => "Failed to delete book"], 400);
            }
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }

    public static function getUsers()
    {
        authenticateAdmin();

        try {
            // Implementation would retrieve all users
            jsonResponse([
                "message" => "User list functionality will be implemented here",
            ]);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }

    public static function deleteUser()
    {
        authenticateAdmin();
        $id = extractIdFromUri();

        try {
            // Implementation would delete a user
            jsonResponse([
                "message" =>
                "User deletion functionality will be implemented here",
            ]);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }
}
