<?php
require_once __DIR__ . "/config/session.php";
require_once __DIR__ . "/utils/auth.php";
require_once __DIR__ . "/controllers/AuthController.php";
require_once __DIR__ . "/controllers/BookController.php";
require_once __DIR__ . "/controllers/CartController.php";
require_once __DIR__ . "/controllers/AdminController.php";

header("Content-Type: application/json");

// Route the request
$request = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

// Public routes
if ($method === "POST" && $request === "/api/register") {
    AuthController::register();
} elseif ($method === "POST" && $request === "/api/login") {
    AuthController::login();
} elseif (
    $method === "GET" &&
    preg_match('/^\/api\/books(\?.*)?$/', $request)
) {
    BookController::getBooks();
} elseif ($method === "GET" && preg_match('/^\/api\/books\/\d+$/', $request)) {
    BookController::getBookDetails();
}

// Check session for protected routes
checkSessionTimeout();

// Authenticated user routes
if ($method === "POST" && $request === "/api/logout") {
    AuthController::logout();
} elseif ($method === "GET" && $request === "/api/cart") {
    CartController::getCart();
} elseif ($method === "POST" && $request === "/api/cart/add") {
    CartController::addToCart();
} elseif ($method === "POST" && $request === "/api/cart/remove") {
    CartController::removeFromCart();
} elseif ($method === "POST" && $request === "/api/checkout") {
    CartController::checkout();
}

// Admin routes
if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
    if ($method === "POST" && $request === "/api/admin/books") {
        AdminController::addBook();
    } elseif (
        $method === "PUT" &&
        preg_match('/^\/api\/admin\/books\/\d+$/', $request)
    ) {
        AdminController::updateBook();
    } elseif (
        $method === "DELETE" &&
        preg_match('/^\/api\/admin\/books\/\d+$/', $request)
    ) {
        AdminController::deleteBook();
    } elseif ($method === "GET" && $request === "/api/admin/users") {
        AdminController::getUsers();
    } elseif (
        $method === "DELETE" &&
        preg_match('/^\/api\/admin\/users\/\d+$/', $request)
    ) {
        AdminController::deleteUser();
    }
}

// 404 Not Found
http_response_code(404);
echo json_encode(["error" => "Endpoint not found"]);
?>
