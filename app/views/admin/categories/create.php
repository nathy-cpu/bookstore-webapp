<h1>Create New Category</h1>

<?php if (isset($error)) : ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="form-container">
    <form action="/admin/categories/create" method="POST">
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" 
                   required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" 
                      name="description" 
                      rows="4"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary">Create Category</button>
            <a href="/admin/categories" class="button button-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 1rem;
    }
    .form-group textarea {
        resize: vertical;
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
</style> 