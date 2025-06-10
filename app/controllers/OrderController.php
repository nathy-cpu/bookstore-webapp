<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Debug.php';

class OrderController {
    private $orderModel;
    private $bookModel;
    private $cartModel;

    public function __construct() {
        Debug::logStackTrace("Constructing OrderController");
        Auth::requireLogin();
        $this->orderModel = new Order();
        $this->bookModel = new Book();
        $this->cartModel = new Cart();
    }

    public function create() {
        Debug::logStackTrace("OrderController->create() called");
        try {
            $userId = $_SESSION['user_id'];
            $fromCart = isset($_POST['from_cart']) && $_POST['from_cart'] === '1';
            $items = [];

            if ($fromCart) {
                // Get items from cart
                $cartItems = $this->cartModel->getItemsWithDetails($userId);
                if (empty($cartItems)) {
                    throw new Exception('Cart is empty');
                }
                $items = array_map(function($item) {
                    return [
                        'book_id' => $item['book_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ];
                }, $cartItems);
            } else {
                // Direct order for a single book
                $bookId = $_POST['book_id'] ?? null;
                if (!$bookId) {
                    throw new Exception('Book ID is required');
                }

                $book = $this->bookModel->getById($bookId);
                if (!$book) {
                    throw new Exception('Book not found');
                }

                $items[] = [
                    'book_id' => $bookId,
                    'quantity' => 1,
                    'price' => $book['price']
                ];
            }

            // Create the order
            if ($this->orderModel->create($userId, $items)) {
                // Clear cart if order was from cart
                if ($fromCart) {
                    $this->cartModel->clearCart($userId);
                }
                Debug::logStackTrace("Order created successfully");
                header('Location: /orders');
                exit;
            }
        } catch (Exception $e) {
            Debug::logStackTrace("Error creating order: " . $e->getMessage());
            $error = $e->getMessage();
            if ($fromCart) {
                header('Location: /cart?error=' . urlencode($error));
            } else {
                header('Location: /books?error=' . urlencode($error));
            }
            exit;
        }
    }

    public function index() {
        Debug::logStackTrace("OrderController->index() called");
        try {
            $userId = $_SESSION['user_id'];
            $orders = $this->orderModel->getByUserId($userId);
            require_once __DIR__ . '/../views/orders/index.php';
        } catch (Exception $e) {
            Debug::logStackTrace("Error fetching orders: " . $e->getMessage());
            $error = 'Failed to load orders';
            require_once __DIR__ . '/../views/orders/index.php';
        }
    }
} 
