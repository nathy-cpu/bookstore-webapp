<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <ul>
        <?php foreach ($items as $item): ?>
            <li>
                Book ID: <?php echo $item['book_id']; ?>
                <form method="POST" action="/cart/remove/<?php echo $item['book_id']; ?>">
                    <button type="submit">Remove</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="/books">Continue Shopping</a>
</body>
</html> 