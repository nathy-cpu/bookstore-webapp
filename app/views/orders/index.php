<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        .order-list {
            margin: 2rem 0;
        }
        .order {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 2rem;
            padding: 1rem;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        .order-status {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .order-items {
            margin: 1rem 0;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .order-total {
            text-align: right;
            font-weight: bold;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <h1>Your Orders</h1>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (empty($orders)) : ?>
        <p>You haven't placed any orders yet. <a href="/books">Browse books</a></p>
    <?php else : ?>
        <div class="order-list">
            <?php
            $currentOrderId = null;
            $orderTotal = 0;
            $orderItems = [];

            foreach ($orders as $order) :
                if ($currentOrderId !== $order['id']) {
                    // Display previous order if exists
                    if ($currentOrderId !== null) {
                        require __DIR__ . '/_order_template.php';
                    }
                    // Start new order
                    $currentOrderId = $order['id'];
                    $orderTotal = $order['total_amount'];
                    $orderItems = [];
                }

                $orderItems[] = [
                    'title' => $order['title'],
                    'author' => $order['author'],
                    'quantity' => $order['quantity'],
                    'price' => $order['price_at_time']
                ];
            endforeach;

            // Display last order
            if ($currentOrderId !== null) {
                require __DIR__ . '/_order_template.php';
            }
            ?>
        </div>
    <?php endif; ?>
</body>
</html> 