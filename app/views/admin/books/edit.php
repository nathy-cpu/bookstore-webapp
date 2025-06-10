<?php
require_once __DIR__ . '/../../../utils/View.php';
View::setLayout('admin');

// Get categories from the database
$categoryModel = new Category();
$categories = $categoryModel->getAll();
?>

<div class="admin-container">
    <div class="admin-header">
        <h1>Edit Book</h1>
        <div class="admin-actions">
            <a href="/admin/books" class="button button-secondary">Back to Books</a>
        </div>
    </div>

    <?php if (isset($error)) : ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <div class="admin-content">
        <form action="/admin/books/edit/<?php echo htmlspecialchars($book['id']); ?>" method="POST" class="admin-form">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="<?php echo htmlspecialchars($book['title']); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" 
                       id="author" 
                       name="author" 
                       value="<?php echo htmlspecialchars($book['author']); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" 
                        name="category_id" 
                        required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['id']; ?>"
                                <?php echo $book['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" 
                          name="description" 
                          rows="5"><?php echo htmlspecialchars($book['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price (ETB)</label>
                <input type="number" 
                       id="price" 
                       name="price" 
                       step="0.01" 
                       min="0" 
                       value="<?php echo htmlspecialchars($book['price']); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" 
                       id="stock" 
                       name="stock" 
                       min="0" 
                       value="<?php echo htmlspecialchars($book['stock']); ?>" 
                       required>
            </div>

            <div class="form-actions">
                <button type="submit" class="button button-primary">Save Changes</button>
                <a href="/admin/books" class="button button-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .admin-container {
        padding: 2rem;
    }
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .admin-content {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .admin-form {
        max-width: 600px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #2d3748;
    }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 1rem;
    }
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
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