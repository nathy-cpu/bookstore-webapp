<?php

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Debug.php';

class CartController
{
    private $cartModel;
    private $bookModel;

    public function __construct()
    {
        Debug::logStackTrace("Constructing CartController");
        Auth::requireLogin();
        $this->cartModel = new Cart();
        $this->bookModel = new Book();
    }

    public function index()
    {
        Debug::logStackTrace("CartController->index() called");
        try {
            $userId = $_SESSION['user_id'];
            $cartItems = $this->cartModel->getItemsWithDetails($userId);
            require_once __DIR__ . '/../views/cart/index.php';
        } catch (Exception $e) {
            Debug::logStackTrace("Error in cart index: " . $e->getMessage());
            $error = 'Failed to load cart items';
            require_once __DIR__ . '/../views/cart/index.php';
        }
    }

    public function add()
    {
        Debug::logStackTrace("CartController->add() called");
        try {
            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;

            if (!$bookId) {
                throw new Exception('Book ID is required');
            }

            // Check if book exists and has stock
            $book = $this->bookModel->getById($bookId);
            if (!$book) {
                throw new Exception('Book not found');
            }
            if ($book['stock'] <= 0) {
                throw new Exception('Book is out of stock');
            }

            // Add to cart
            if ($this->cartModel->addItem($userId, $bookId, $quantity)) {
                Debug::logStackTrace("Item added to cart successfully");

                // If it's an AJAX request, return JSON response
                if (
                    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
                ) {
                    header('Content-Type: application/json');
                    $cartCount = $this->cartModel->getCartItemCount($userId);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Item added to cart successfully',
                        'cartCount' => $cartCount
                    ]);
                    exit;
                }

                // For non-AJAX requests, redirect to cart page
                header('Location: /cart');
                exit;
            } else {
                throw new Exception('Failed to add item to cart');
            }
        } catch (Exception $e) {
            Debug::logStackTrace("Error adding to cart: " . $e->getMessage());
            if (
                !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            }
            $error = $e->getMessage();
            $this->index();
        }
    }

    public function update()
    {
        Debug::logStackTrace("CartController->update() called");
        try {
            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'] ?? null;
            $quantity = $_POST['quantity'] ?? null;

            if (!$bookId || !$quantity) {
                throw new Exception('Book ID and quantity are required');
            }

            // Check if book exists and has enough stock
            $book = $this->bookModel->getById($bookId);
            if (!$book) {
                throw new Exception('Book not found');
            }
            if ($book['stock'] < $quantity) {
                throw new Exception('Not enough stock available');
            }

            // Update cart quantity
            if ($this->cartModel->updateQuantity($userId, $bookId, $quantity)) {
                Debug::logStackTrace("Cart quantity updated successfully");
                header('Location: /cart');
                exit;
            } else {
                throw new Exception('Failed to update cart');
            }
        } catch (Exception $e) {
            Debug::logStackTrace("Error updating cart: " . $e->getMessage());
            $error = $e->getMessage();
            $this->index();
        }
    }

    public function remove()
    {
        Debug::logStackTrace("CartController->remove() called");
        try {
            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'] ?? null;

            if (!$bookId) {
                throw new Exception('Book ID is required');
            }

            if ($this->cartModel->removeItem($userId, $bookId)) {
                Debug::logStackTrace("Item removed from cart successfully");
                header('Location: /cart');
                exit;
            } else {
                throw new Exception('Failed to remove item from cart');
            }
        } catch (Exception $e) {
            Debug::logStackTrace("Error removing from cart: " . $e->getMessage());
            $error = $e->getMessage();
            $this->index();
        }
    }

    public function clear()
    {
        Debug::logStackTrace("CartController->clear() called");
        try {
            $userId = $_SESSION['user_id'];
            if ($this->cartModel->clearCart($userId)) {
                Debug::logStackTrace("Cart cleared successfully");
                header('Location: /cart');
                exit;
            } else {
                throw new Exception('Failed to clear cart');
            }
        } catch (Exception $e) {
            Debug::logStackTrace("Error clearing cart: " . $e->getMessage());
            $error = $e->getMessage();
            $this->index();
        }
    }

    public function getCartCount()
    {
        Debug::logStackTrace("CartController->getCartCount() called");
        try {
            $userId = $_SESSION['user_id'];
            $count = $this->cartModel->getCartItemCount($userId);
            header('Content-Type: application/json');
            echo json_encode(['count' => $count]);
        } catch (Exception $e) {
            Debug::logStackTrace("Error getting cart count: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
}
