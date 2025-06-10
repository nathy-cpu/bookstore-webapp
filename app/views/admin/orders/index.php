<?php
require_once __DIR__ . '/../../../utils/View.php';
View::setLayout('admin');
?>

<div class="admin-container">
    <div class="admin-header">
        <h1>Orders Management</h1>
    </div>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="admin-content">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td>
                                <?php
                                $userName = trim($order['user_first_name'] . ' ' . $order['user_last_name']);
                                if (empty($userName)) {
                                    $userName = $order['user_email'];
                                }
                                echo htmlspecialchars($userName);
                                ?>
                            </td>
                            <td>Br <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($order['status'] ?? 'pending'); ?>">
                                    <?php echo htmlspecialchars($order['status'] ?? 'PENDING'); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                            <td class="actions">
                                <a href="/admin/orders/<?php echo $order['id']; ?>" 
                                   class="button button-small">View Details</a>
                                <?php if (($order['status'] ?? 'PENDING') === 'PENDING') : ?>
                                    <form action="/admin/orders/<?php echo $order['id']; ?>/process" 
                                          method="POST" 
                                          class="inline-form">
                                        <button type="submit" class="button button-small button-primary">Process</button>
                                    </form>
                                <?php endif; ?>
                                <?php if (($order['status'] ?? '') === 'PROCESSING') : ?>
                                    <form action="/admin/orders/<?php echo $order['id']; ?>/complete" 
                                          method="POST" 
                                          class="inline-form">
                                        <button type="submit" class="button button-small button-success">Complete</button>
                                    </form>
                                <?php endif; ?>
                                <?php if (($order['status'] ?? 'PENDING') === 'PENDING') : ?>
                                    <form action="/admin/orders/<?php echo $order['id']; ?>/cancel" 
                                          method="POST" 
                                          class="inline-form"
                                          onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <button type="submit" class="button button-small button-danger">Cancel</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
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

    .button-success {
        background-color: #22c55e;
        color: white;
    }

    .button-success:hover {
        background-color: #16a34a;
    }
</style> 