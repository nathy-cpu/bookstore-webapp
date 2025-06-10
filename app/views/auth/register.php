<?php
require_once __DIR__ . '/../../utils/View.php';
View::setLayout('base');
?>

<div class="auth-container">
    <div class="auth-header">
        <h1>Create Your Account</h1>
        <p class="auth-subtitle">Join our community today</p>
    </div>

    <?php if (isset($error)) : ?>
        <div class="alert alert-error">
            <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
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
                   required
                   placeholder="Enter your first name">
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" 
                   id="last_name" 
                   name="last_name" 
                   value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" 
                   required
                   placeholder="Enter your last name">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" 
                   required
                   placeholder="your@email.com">
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number <span class="optional-text">(Optional)</span></label>
            <input type="tel" 
                   id="phone_number" 
                   name="phone_number" 
                   value="<?php echo htmlspecialchars($old['phone_number'] ?? ''); ?>"
                   pattern="[0-9+\-\s()]*"
                   placeholder="+251-91-234-5678">
            <small class="form-help">Enter a valid phone number (e.g., +251-91-234-5678)</small>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   required
                   placeholder="••••••••">
            <small class="form-help">Password must be at least 8 characters long</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" 
                   id="confirm_password" 
                   name="confirm_password" 
                   required
                   placeholder="••••••••">
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary">
                <span>Register</span>
                <svg class="button-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div class="auth-link">
                Already have an account? <a href="/login">Login</a>
            </div>
        </div>
    </form>
</div>

<style>
    .auth-container {
        max-width: 480px;
        margin: 3rem auto;
        padding: 2.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .auth-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .auth-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .auth-subtitle {
        color: #718096;
        font-size: 0.9375rem;
    }

    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .form-group {
        margin-bottom: 0.75rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #2d3748;
        font-size: 0.9375rem;
    }

    .optional-text {
        color: #718096;
        font-weight: 400;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9375rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
    }

    .form-group input::placeholder {
        color: #a0aec0;
    }

    .form-help {
        display: block;
        margin-top: 0.375rem;
        font-size: 0.8125rem;
        color: #718096;
        line-height: 1.4;
    }

    .form-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .button-primary {
        background-color: #4299e1;
        color: white;
    }

    .button-primary:hover {
        background-color: #3182ce;
    }

    .button-icon {
        width: 1.25rem;
        height: 1.25rem;
        margin-left: 0.5rem;
    }

    .auth-link {
        text-align: center;
        font-size: 0.9375rem;
        color: #4a5568;
    }

    .auth-link a {
        color: #4299e1;
        text-decoration: none;
        font-weight: 500;
    }

    .auth-link a:hover {
        text-decoration: underline;
    }

    .alert {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 6px;
        font-size: 0.9375rem;
    }

    .alert-error {
        background-color: #fff5f5;
        color: #c53030;
        border: 1px solid #fed7d7;
    }

    .alert-icon {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }
</style>