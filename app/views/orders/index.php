<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
</head>
<body>
    <h1>Your Orders</h1>
    <ul>
        <?php foreach ($orders as $order): ?>
            <li>Order ID: <?php echo $order['id']; ?>, Book ID: <?php echo $order['book_id']; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="/orders/create">Create New Order</a>
</body>
</html> 