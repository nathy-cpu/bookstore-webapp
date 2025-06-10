<?php
require_once __DIR__ . '/../../../utils/View.php';
View::setLayout('admin');
?>

<div class="admin-container">
    <div class="admin-header">
        <h1>Order Details #<?php echo htmlspecialchars($order[0]['id']); ?></h1>
        <div class="admin-actions">
            <a href="/admin/orders" class="button button-secondary">Back to Orders</a>
        </div>
    </div>

    <?php if (isset($error)) : ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="admin-content">
        <div class="order-info">
            <div class="info-section">
                <h2>Order Information</h2>
                <div class="table-responsive">
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($order[0]['status'] ?? 'pending'); ?>">
                                        <?php echo htmlspecialchars($order[0]['status'] ?? 'PENDING'); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td><?php echo htmlspecialchars($order[0]['created_at']); ?></td>
                            </tr>
                            <tr>
                                <th>Total Amount</th>
                                <td>Br <?php echo number_format($order[0]['total_amount'], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="info-section">
                <h2>Customer Information</h2>
                <div class="table-responsive">
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>
                                    <?php
                                    $userName = trim($order[0]['user_first_name'] . ' ' . $order[0]['user_last_name']);
                                    echo $userName ? htmlspecialchars($userName) : '<span class="text-muted">Not set</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo htmlspecialchars($order[0]['user_email']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="info-section">
                <h2>Order Items</h2>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Author</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order as $item) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                                    <td><?php echo htmlspecialchars($item['author']); ?></td>
                                    <td>Br <?php echo number_format($item['price_at_time'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                    <td>Br <?php echo number_format($item['price_at_time'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td><strong>Br <?php echo number_format($order[0]['total_amount'], 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <?php if ($order[0]['status'] === 'PENDING') : ?>
                <div class="order-actions">
                    <form action="/admin/orders/<?php echo $order[0]['id']; ?>/process" method="POST" class="inline-form">
                        <button type="submit" class="button button-primary">Process Order</button>
                    </form>
                    <form action="/admin/orders/<?php echo $order[0]['id']; ?>/cancel" 
                          method="POST" 
                          class="inline-form"
                          onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        <button type="submit" class="button button-danger">Cancel Order</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($order[0]['status'] === 'PROCESSING') : ?>
                <div class="order-actions">
                    <form action="/admin/orders/<?php echo $order[0]['id']; ?>/complete" method="POST" class="inline-form">
                        <button type="submit" class="button button-success">Complete Order</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .admin-container {
        max-width: 100%;
        margin: 0;
        padding: 2rem;
    }

    .order-info {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        width: 100%;
    }

    .info-section {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .info-section h2 {
        margin: 0 0 1rem 0;
        font-size: 1.25rem;
        color: #2d3748;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .info-table th,
    .info-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        word-wrap: break-word;
    }

    .info-table th {
        width: 25%;
        color: #4a5568;
        font-weight: 500;
        background-color: #f7fafc;
    }

    .order-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        width: 100%;
        justify-content: flex-start;
    }

    .text-right {
        text-align: right;
    }

    .text-muted {
        color: #718096;
        font-style: italic;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        margin: 0;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
    }

    .admin-table th,
    .admin-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        word-wrap: break-word;
    }

    .admin-table thead th {
        background-color: #f7fafc;
        color: #4a5568;
        font-weight: 500;
        white-space: nowrap;
    }

    .admin-table tbody tr:hover {
        background-color: #f7fafc;
    }

    .admin-table tfoot {
        background-color: #f7fafc;
        font-weight: 500;
    }

    /* Column widths for order items table */
    .admin-table th:nth-child(1), /* Book title */
    .admin-table td:nth-child(1) {
        width: 30%;
    }

    .admin-table th:nth-child(2), /* Author */
    .admin-table td:nth-child(2) {
        width: 25%;
    }

    .admin-table th:nth-child(3), /* Price */
    .admin-table td:nth-child(3),
    .admin-table th:nth-child(4), /* Quantity */
    .admin-table td:nth-child(4),
    .admin-table th:nth-child(5), /* Subtotal */
    .admin-table td:nth-child(5) {
        width: 15%;
    }

    /* Status badge styles */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        text-align: center;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-processing {
        background-color: #e0f2fe;
        color: #075985;
    }

    .status-completed {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }

        .info-table th {
            width: 35%;
        }

        .admin-table {
            display: block;
            overflow-x: auto;
        }

        .admin-table th,
        .admin-table td {
            min-width: 120px;
        }
    }
</style> 