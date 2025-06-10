<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error { color: red; margin-bottom: 1em; }
        .system-error { color: #d32f2f; background: #ffebee; padding: 1em; border-radius: 4px; margin-bottom: 1em; }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) : ?>
        <p class="<?php echo strpos($error, 'system') !== false ? 'system-error' : 'error'; ?>">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>
    <form method="POST" action="/login">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="/register">Register</a></p>
</body>
</html> 