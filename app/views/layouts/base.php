<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <!-- Add timestamp to prevent caching during development -->
    <link rel="stylesheet" href="/public/css/main.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Debug output
    if (isset($_ENV['DEBUG']) && $_ENV['DEBUG']) {
        echo "<!-- Current URI: " . $_SERVER['REQUEST_URI'] . " -->\n";
        echo "<!-- Document Root: " . $_SERVER['DOCUMENT_ROOT'] . " -->\n";
    }
    ?>
    <header class="site-header">
        <div class="container">
            <nav class="main-nav">
                <div class="nav-brand">
                    <a href="/">Bookstore</a>
                </div>
                <div class="nav-links">
                    <a href="/books">Books</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/cart">Cart (<span id="cart-count">0</span>)</a>
                        <a href="/orders">Orders</a>
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                            <a href="/admin">Admin</a>
                        <?php endif; ?>
                        <a href="/logout">Logout</a>
                    <?php else: ?>
                        <a href="/login">Login</a>
                        <a href="/register">Register</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="site-main">
        <div class="container">
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php echo $content; ?>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Bookstore. All rights reserved.</p>
        </div>
    </footer>

    <script src="/public/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html> 