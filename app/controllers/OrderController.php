<?php

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
        Auth::requireLogin();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'];
            if ($this->orderModel->create($userId, $bookId)) {
                header('Location: /orders');
            } else {
                $error = 'Order creation failed';
            }
        }
        require_once __DIR__ . '/../views/orders/create.php';
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getByUserId($userId);
        require_once __DIR__ . '/../views/orders/index.php';
    }
} 