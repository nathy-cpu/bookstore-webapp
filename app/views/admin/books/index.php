<?php
require_once __DIR__ . '/../../../utils/View.php';
View::setLayout('admin');
?>

<link rel="stylesheet" href="/public/css/admin/books.css?v=<?php echo time(); ?>">

<div class="admin-container">
    <div class="admin-header">
        <h1>Books Management</h1>
        <div class="admin-actions">
            <a href="/admin/books/create" class="button button-primary">Add New Book</a>
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
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['id']); ?></td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td>
                                <?php echo $book['category_name'] ? htmlspecialchars($book['category_name']) : '<span class="text-muted">Uncategorized</span>'; ?>
                            </td>
                            <td>Br <?php echo number_format($book['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($book['stock']); ?></td>
                            <td class="actions">
                                <a href="/admin/books/edit/<?php echo $book['id']; ?>" 
                                   class="button button-small">Edit</a>
                                <form action="/admin/books/delete/<?php echo $book['id']; ?>" 
                                      method="POST" 
                                      class="inline-form"
                                      onsubmit="return confirm('Are you sure you want to delete this book?');">
                                    <button type="submit" class="button button-small button-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
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
    .actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .button-small {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .inline-form {
        display: inline;
    }
    .text-muted {
        color: #718096;
        font-style: italic;
    }
</style> 