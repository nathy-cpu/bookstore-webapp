<?php
require_once __DIR__ . '/../../../utils/View.php';
View::setLayout('admin');
?>

<div class="admin-container">
    <div class="admin-header">
        <h1>Edit User</h1>
        <div class="admin-actions">
            <a href="/admin/users" class="button button-secondary">Back to Users</a>
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
        <form action="/admin/users/edit/<?php echo htmlspecialchars($user['id']); ?>" method="POST" class="admin-form">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" 
                       id="first_name" 
                       name="first_name" 
                       value="<?php echo htmlspecialchars($user['first_name']); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" 
                       id="last_name" 
                       name="last_name" 
                       value="<?php echo htmlspecialchars($user['last_name']); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number (Optional)</label>
                <input type="tel" 
                       id="phone_number" 
                       name="phone_number" 
                       value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>"
                       pattern="[0-9+\-\s()]*">
                <small class="form-help">Enter a valid phone number (e.g., +251-91-234-5678)</small>
            </div>

            <div class="form-group">
                <label for="new_password">New Password (leave blank to keep current)</label>
                <input type="password" 
                       id="new_password" 
                       name="new_password">
                <small class="form-help">Password must be at least 8 characters long</small>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" 
                           name="is_admin" 
                           <?php echo $user['is_admin'] ? 'checked' : ''; ?>>
                    Administrator Access
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="button button-primary">Save Changes</button>
                <a href="/admin/users" class="button button-secondary">Cancel</a>
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
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 1rem;
    }
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    .form-help {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #718096;
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