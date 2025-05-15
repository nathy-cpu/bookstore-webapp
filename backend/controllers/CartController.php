<?php
require_once __DIR__ . "./../models/Cart.php";
require_once __DIR__ . "./../models/Book.php";
require_once __DIR__ . "./../utils/auth.php";
require_once __DIR__ . "./../utils/helpers.php";

class CartController
{
    public static function getCart()
    {
        authenticateUser();

        try {
            $cart = new Cart();
            $items = $cart->getCart($_SESSION["user_id"]);
            jsonResponse($items);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    public static function addToCart()
    {
        authenticateUser();
        $data = getJsonInput();

        try {
            $required = ["book_id"];
            validateRequiredFields($data, $required);

            $cart = new Cart();
            $quantity = $data["quantity"] ?? 1;
            $items = $cart->addToCart(
                $_SESSION["user_id"],
                $data["book_id"],
                $quantity
            );

            jsonResponse($items);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }

    public static function removeFromCart()
    {
        authenticateUser();
        $data = getJsonInput();

        try {
            $required = ["cart_id"];
            validateRequiredFields($data, $required);

            $cart = new Cart();
            $success = $cart->removeFromCart(
                $_SESSION["user_id"],
                $data["cart_id"]
            );

            if ($success) {
                jsonResponse(["message" => "Item removed from cart"]);
            } else {
                jsonResponse(["error" => "Failed to remove item"], 400);
            }
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }

    public static function checkout()
    {
        authenticateUser();

        try {
            // Implementation would include:
            // 1. Validate cart items
            // 2. Create order
            // 3. Process payment
            // 4. Clear cart
            // 5. Send confirmation

            jsonResponse([
                "message" => "Checkout functionality will be implemented here",
            ]);
        } catch (Exception $e) {
            jsonResponse(["error" => $e->getMessage()], 400);
        }
    }
}
