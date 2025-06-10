<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bookstore</title>
    <link rel="stylesheet" href="/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/css/admin.css?v=<?php echo time(); ?>">
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

    <script src="/js/admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<style>
/* These styles will be moved to admin.css later */
.admin-body {
    margin: 0;
    padding: 0;
    background-color: #f7fafc;
}

.admin-wrapper {
    display: flex;
    min-height: 100vh;
}

.admin-sidebar {
    width: 250px;
    background-color: #2d3748;
    color: white;
    padding: 1rem;
}

.admin-sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid #4a5568;
}

.admin-sidebar-header h1 {
    margin: 0;
    font-size: 1.25rem;
    color: white;
}

.admin-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
}

.admin-nav-item {
    display: block;
    padding: 0.75rem 1rem;
    color: #e2e8f0;
    text-decoration: none;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.admin-nav-item:hover,
.admin-nav-item.active {
    background-color: #4a5568;
    color: white;
}

.admin-main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.admin-header {
    background-color: white;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 2rem;
}

.admin-header-content {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.admin-user-menu {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.admin-content-wrapper {
    flex: 1;
    padding: 2rem;
}

.button {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.2s;
}

.button-primary {
    background-color: #4299e1;
    color: white;
}

.button-primary:hover {
    background-color: #3182ce;
}

.button-secondary {
    background-color: #edf2f7;
    color: #2d3748;
}

.button-secondary:hover {
    background-color: #e2e8f0;
}

.button-danger {
    background-color: #f56565;
    color: white;
}

.button-danger:hover {
    background-color: #e53e3e;
}

.admin-container {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.admin-actions {
    display: flex;
    gap: 1rem;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.375rem;
}

.alert-success {
    background-color: #f0fff4;
    color: #2f855a;
    border: 1px solid #9ae6b4;
}

.alert-error {
    background-color: #fff5f5;
    color: #c53030;
    border: 1px solid #feb2b2;
}
</style> 