<div class="order">
    <div class="order-header">
        <div>
            <h3>Order #<?php echo $currentOrderId; ?></h3>
            <p>Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
        </div>
        <span class="order-status status-<?php echo strtolower($order['status']); ?>">
            <?php echo ucfirst($order['status']); ?>
        </span>
    </div>
    <div class="order-items">
        <?php foreach ($orderItems as $item) : ?>
            <div class="order-item">
                <div>
                    <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                    <p>by <?php echo htmlspecialchars($item['author']); ?></p>
                </div>
                <div>
                    <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="order-total">
        Total: $<?php echo number_format($orderTotal, 2); ?>
    </div>
</div> 