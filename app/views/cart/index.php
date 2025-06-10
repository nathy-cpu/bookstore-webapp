<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .cart-table th,
        .cart-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .cart-total {
            text-align: right;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .cart-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        .button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .button-primary {
            background-color: #2c5282;
            color: white;
        }
        .button-danger {
            background-color: #e53e3e;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="/books">Browse books</a></p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                foreach ($cartItems as $item): 
                    $itemTotal = $item['price'] * $item['quantity'];
                    $total += $itemTotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars($item['author']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form action="/cart/update" method="POST" style="display: inline;">
                                <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="<?php echo $item['stock']; ?>" 
                                       style="width: 60px;" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>$<?php echo number_format($itemTotal, 2); ?></td>
                        <td>
                            <form action="/cart/remove" method="POST" style="display: inline;">
                                <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                                <button type="submit" class="button button-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-total">
            <strong>Total: $<?php echo number_format($total, 2); ?></strong>
        </div>

        <div class="cart-actions">
            <form action="/cart/clear" method="POST" style="display: inline;">
                <button type="submit" class="button button-danger">Clear Cart</button>
            </form>
            <form action="/orders/create" method="POST" style="display: inline;">
                <input type="hidden" name="from_cart" value="1">
                <button type="submit" class="button button-primary">Checkout</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html> 