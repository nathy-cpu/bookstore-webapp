<?php

require_once __DIR__ . '/../utils/Database.php';
require_once __DIR__ . '/../utils/Debug.php';

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $items)
    {
        Debug::logStackTrace("Creating order for user: " . $userId);
        try {
            // Calculate total amount
            $totalAmount = 0;
            foreach ($items as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Start transaction
            $this->db->beginTransaction();

            // Create order
            $stmt = $this->db->prepare('
                INSERT INTO orders (user_id, total_amount, status) 
                VALUES (?, ?, ?)
            ');
            $stmt->execute([$userId, $totalAmount, 'pending']);
            $orderId = $this->db->lastInsertId();

            // Add order items
            $stmt = $this->db->prepare('
                INSERT INTO order_items (order_id, book_id, quantity, price_at_time) 
                VALUES (?, ?, ?, ?)
            ');
            foreach ($items as $item) {
                $stmt->execute([
                    $orderId,
                    $item['book_id'],
                    $item['quantity'],
                    $item['price']
                ]);

                // Update book stock
                $updateStmt = $this->db->prepare('
                    UPDATE books 
                    SET stock = stock - ? 
                    WHERE id = ? AND stock >= ?
                ');
                $result = $updateStmt->execute([
                    $item['quantity'],
                    $item['book_id'],
                    $item['quantity']
                ]);

                if (!$result || $updateStmt->rowCount() === 0) {
                    throw new Exception("Not enough stock for book ID: " . $item['book_id']);
                }
            }

            // Commit transaction
            $this->db->commit();
            Debug::logStackTrace("Order created successfully: " . $orderId);
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            Debug::logStackTrace("Error creating order: " . $e->getMessage());
            throw $e;
        }
    }

    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare('
            SELECT o.*, oi.book_id, oi.quantity, oi.price_at_time, b.title, b.author
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN books b ON oi.book_id = b.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById($orderId)
    {
        $stmt = $this->db->prepare('
            SELECT o.*, 
                   oi.book_id, oi.quantity, oi.price_at_time, 
                   b.title, b.author,
                   u.email as user_email,
                   u.first_name as user_first_name,
                   u.last_name as user_last_name
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN books b ON oi.book_id = b.id
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare('
            SELECT DISTINCT o.*, 
                   u.email as user_email,
                   u.first_name as user_first_name,
                   u.last_name as user_last_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus($orderId, $status)
    {
        $validStatuses = ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'];
        if (!in_array(strtoupper($status), $validStatuses)) {
            throw new Exception('Invalid order status');
        }

        $stmt = $this->db->prepare('
            UPDATE orders 
            SET status = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ');
        return $stmt->execute([strtoupper($status), $orderId]);
    }
}
