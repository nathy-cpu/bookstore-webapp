<?php
require_once __DIR__ . '/../../../utils/View.php';
View::setLayout('admin');
?>

<div class="admin-container">
    <div class="admin-header">
        <h1>Users Management</h1>
        <div class="admin-actions">
            <a href="/admin/users/create" class="button button-primary">Create User</a>
        </div>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php echo $user['phone_number'] ? htmlspecialchars($user['phone_number']) : '<span class="text-muted">Not provided</span>'; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $user['is_admin'] ? 'badge-admin' : 'badge-user'; ?>">
                                    <?php echo $user['is_admin'] ? 'Admin' : 'User'; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('Y-m-d', strtotime($user['created_at'])); ?>
                            </td>
                            <td class="actions">
                                <a href="/admin/users/edit/<?php echo $user['id']; ?>" 
                                   class="button button-small">Edit</a>
                                <?php if ($user['id'] != $_SESSION['user_id']) : ?>
                                    <form action="/admin/users/delete/<?php echo $user['id']; ?>" 
                                          method="POST" 
                                          class="inline-form"
                                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <button type="submit" class="button button-small button-danger">Delete</button>
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
    .admin-container {
        padding: 2rem;
    }
    .admin-header {
        margin-bottom: 2rem;
    }
    .admin-content {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table-responsive {
        overflow-x: auto;
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    .admin-table th,
    .admin-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .admin-table th {
        background-color: #f7fafc;
        font-weight: 600;
        color: #4a5568;
    }
    .admin-table tbody tr:hover {
        background-color: #f7fafc;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .badge-admin {
        background-color: #ebf8ff;
        color: #2b6cb0;
    }
    .badge-user {
        background-color: #f0fff4;
        color: #2f855a;
    }
    .actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .button-small {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .button-danger {
        background-color: #f56565;
        color: white;
    }
    .button-danger:hover {
        background-color: #e53e3e;
    }
    .inline-form {
        display: inline;
    }
    .text-muted {
        color: #718096;
        font-style: italic;
    }
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }
    .alert-success {
        background-color: #f0fff4;
        color: #2f855a;
        border: 1px solid #9ae6b4;
    }
    .alert-error {
        background-color: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }
</style> 