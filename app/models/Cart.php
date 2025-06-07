<?php
require_once __DIR__ . "/../config/database.php";

class Cart
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getDBConnection();
    }
    /**
     * @param mixed $user_id
     */
    public function getCart($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT c.cart_id, c.quantity, b.book_id, b.title, b.author, b.price
            FROM cart c
            JOIN books b ON c.book_id = b.book_id
            WHERE c.user_id = :user_id
        ");

        $stmt->execute([":user_id" => $user_id]);
        return $stmt->fetchAll();
    }
    /**
     * @param mixed $user_id
     * @param mixed $book_id
     * @param mixed $quantity
     */
    public function addToCart($user_id, $book_id, $quantity = 1)
    {
        // Check if item already exists in cart
        $stmt = $this->pdo->prepare("
            SELECT cart_id, quantity FROM cart
            WHERE user_id = :user_id AND book_id = :book_id
        ");

        $stmt->execute([":user_id" => $user_id, ":book_id" => $book_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update quantity if item exists
            $new_quantity = $existing["quantity"] + $quantity;
            $stmt = $this->pdo->prepare("
                UPDATE cart SET quantity = :quantity
                WHERE cart_id = :cart_id
            ");
            $stmt->execute([
                ":quantity" => $new_quantity,
                ":cart_id" => $existing["cart_id"],
            ]);
        } else {
            // Add new item to cart
            $stmt = $this->pdo->prepare("
                INSERT INTO cart (user_id, book_id, quantity)
                VALUES (:user_id, :book_id, :quantity)
            ");
            $stmt->execute([
                ":user_id" => $user_id,
                ":book_id" => $book_id,
                ":quantity" => $quantity,
            ]);
        }

        return $this->getCart($user_id);
    }
    /**
     * @param mixed $user_id
     * @param mixed $cart_id
     */
    public function removeFromCart($user_id, $cart_id)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM cart
            WHERE cart_id = :cart_id AND user_id = :user_id
        ");

        return $stmt->execute([":cart_id" => $cart_id, ":user_id" => $user_id]);
    }
    /**
     * @param mixed $user_id
     */
    public function clearCart($user_id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM cart WHERE user_id = :user_id"
        );
        return $stmt->execute([":user_id" => $user_id]);
    }
}
