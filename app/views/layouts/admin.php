<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bookstore</title>
    <link rel="stylesheet" href="/public/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/public/css/admin/main.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h1>Bookstore Admin</h1>
            </div>
            <nav class="admin-nav">
                <a href="/admin" class="admin-nav-item <?php echo $_SERVER['REQUEST_URI'] === '/admin' ? 'active' : ''; ?>">
                    Dashboard
                </a>
                <a href="/admin/books" class="admin-nav-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/books') === 0 ? 'active' : ''; ?>">
                    Books
                </a>
                <a href="/admin/categories" class="admin-nav-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/categories') === 0 ? 'active' : ''; ?>">
                    Categories
                </a>
                <a href="/admin/users" class="admin-nav-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0 ? 'active' : ''; ?>">
                    Users
                </a>
                <a href="/admin/orders" class="admin-nav-item <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/orders') === 0 ? 'active' : ''; ?>">
                    Orders
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Navigation -->
            <header class="admin-header">
                <div class="admin-header-content">
                    <div class="admin-user-menu">
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                        <a href="/" class="button button-secondary">View Site</a>
                        <a href="/logout" class="button button-secondary">Logout</a>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <div class="admin-content-wrapper">
                <?php echo $content; ?>
            </div>
        </main>
    </div>

    <script src="/public/js/admin/main.js?v=<?php echo time(); ?>"></script>
</body>
</html> 