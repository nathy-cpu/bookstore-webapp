<h1>Manage Categories</h1>

<div class="admin-actions">
    <a href="/admin/categories/create" class="button button-primary">Add New Category</a>
</div>

<?php if (isset($_GET['error'])) : ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])) : ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Books</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['id']); ?></td>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><?php echo htmlspecialchars($category['description'] ?? ''); ?></td>
                    <td>
                        <a href="/books?category=<?php echo $category['id']; ?>">
                            View Books
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($category['created_at']); ?></td>
                    <td class="actions">
                        <a href="/admin/categories/<?php echo $category['id']; ?>/edit" 
                           class="button button-small button-secondary">
                            Edit
                        </a>
                        <form action="/admin/categories/<?php echo $category['id']; ?>/delete" 
                              method="POST" 
                              style="display: inline;"
                              onsubmit="return confirm('Are you sure you want to delete this category?');">
                            <button type="submit" class="button button-small button-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    .admin-actions {
        margin-bottom: 2rem;
    }
    .table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow-x: auto;
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }
    .admin-table th,
    .admin-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .admin-table th {
        background: #f7fafc;
        font-weight: 600;
    }
    .admin-table tr:hover {
        background: #f7fafc;
    }
    .actions {
        white-space: nowrap;
    }
    .button-small {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .button-danger {
        background-color: #e53e3e;
        color: white;
    }
    .button-danger:hover {
        background-color: #c53030;
    }
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }
    .alert-error {
        background-color: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }
    .alert-success {
        background-color: #f0fff4;
        color: #2f855a;
        border: 1px solid #9ae6b4;
    }
</style> 