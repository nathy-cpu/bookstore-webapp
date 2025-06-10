<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <!-- Add timestamp to prevent caching during development -->
    <link rel="stylesheet" href="/css/main.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Debug output
    if (isset($_ENV['DEBUG']) && $_ENV['DEBUG']) {
        echo "<!-- Current URI: " . $_SERVER['REQUEST_URI'] . " -->\n";
        echo "<!-- Document Root: " . $_SERVER['DOCUMENT_ROOT'] . " -->\n";
    }
    ?>
    <header>
        <nav>
            <div class="container">
                <a href="/">Home</a>
                <a href="/books">Books</a>
                <a href="/cart">Cart (<span id="cart-count">0</span>)</a>
                <a href="/orders">Orders</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
                    <a href="/admin">Admin</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <a href="/logout">Logout</a>
                <?php else : ?>
                    <a href="/login">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container">
        <?php if (isset($_GET['error'])) : ?>
            <div class="flash-message flash-error">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])) : ?>
            <div class="flash-message flash-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php echo $content; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Bookstore</p>
        </div>
    </footer>

    <script src="/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html> 