<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../utils/Auth.php';

class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
        Auth::requireLogin();
    }

    public function add($bookId) {
        $userId = $_SESSION['user_id'];
        if ($this->cartModel->addItem($userId, $bookId)) {
            header('Location: /cart');
        } else {
            $error = 'Failed to add item to cart';
        }
        require_once __DIR__ . '/../views/cart/index.php';
    }

    public function remove($bookId) {
        $userId = $_SESSION['user_id'];
        if ($this->cartModel->removeItem($userId, $bookId)) {
            header('Location: /cart');
        } else {
            $error = 'Failed to remove item from cart';
        }
        require_once __DIR__ . '/../views/cart/index.php';
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $items = $this->cartModel->getItems($userId);
        require_once __DIR__ . '/../views/cart/index.php';
    }
} 