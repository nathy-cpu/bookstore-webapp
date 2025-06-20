<?php
require_once __DIR__ . '/../../utils/View.php';
View::setLayout('base');
?>

<h1>Shopping Cart</h1>
<?php if (isset($error)) : ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if (empty($cartItems)) : ?>
    <p>Your cart is empty. <a href="/books">Browse books</a></p>
<?php else : ?>
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
            foreach ($cartItems as $item) :
                $itemTotal = $item['price'] * $item['quantity'];
                $total += $itemTotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['author']); ?></td>
                    <td>Br <?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <form action="/cart/update" method="POST" style="display: inline;">
                            <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                   min="1" max="<?php echo $item['stock']; ?>" 
                                   style="width: 60px;" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td>Br <?php echo number_format($itemTotal, 2); ?></td>
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
        <strong>Total: Br <?php echo number_format($total, 2); ?></strong>
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

<style>
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .cart-table th,
    .cart-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .cart-table th {
        background: #f7fafc;
        font-weight: 600;
        color: #2d3748;
    }
    .cart-table tr:last-child td {
        border-bottom: none;
    }
    .cart-total {
        text-align: right;
        font-size: 1.2rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        transition: background-color 0.2s;
    }
    .button-primary {
        background-color: #2c5282;
        color: white;
    }
    .button-primary:hover {
        background-color: #2a4365;
    }
    .button-danger {
        background-color: #e53e3e;
        color: white;
    }
    .button-danger:hover {
        background-color: #c53030;
    }
</style> 