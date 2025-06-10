<?php
require_once __DIR__ . '/../../utils/View.php';
View::setLayout('base');
?>

<div class="auth-container">
    <h1>Register</h1>

    <?php if (isset($error)) : ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="/register" method="POST" class="auth-form">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" 
                   id="first_name" 
                   name="first_name" 
                   value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>" 
                   required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" 
                   id="last_name" 
                   name="last_name" 
                   value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" 
                   required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" 
                   required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number (Optional)</label>
            <input type="tel" 
                   id="phone_number" 
                   name="phone_number" 
                   value="<?php echo htmlspecialchars($old['phone_number'] ?? ''); ?>"
                   pattern="[0-9+\-\s()]*">
            <small class="form-help">Enter a valid phone number (e.g., +251-91-234-5678)</small>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   required>
            <small class="form-help">Password must be at least 8 characters long</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" 
                   id="confirm_password" 
                   name="confirm_password" 
                   required>
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary">Register</button>
            <a href="/login" class="button button-secondary">Already have an account?</a>
        </div>
    </form>
</div>

<style>
    .auth-container {
        max-width: 500px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #2d3748;
    }
    .form-group input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 1rem;
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
        margin-top: 1rem;
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