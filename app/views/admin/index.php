<?php
require_once __DIR__ . '/../../utils/View.php';
View::setLayout('admin');
?>

<div class="admin-container">
    <div class="admin-header">
        <h1>Dashboard</h1>
    </div>

    <div class="dashboard-grid">
        <!-- Quick Stats -->
        <div class="dashboard-card">
            <h3>Total Books</h3>
            <p class="stat"><?php echo count($books); ?></p>
        </div>

        <div class="dashboard-card">
            <h3>Total Users</h3>
            <p class="stat"><?php echo count($users); ?></p>
        </div>

        <div class="dashboard-card">
            <h3>Out of Stock</h3>
            <p class="stat"><?php
                $outOfStock = array_filter($books, function ($book) {
                    return $book['stock'] <= 0;
                });
                echo count($outOfStock);
                ?></p>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Recent Users</h2>
            <a href="/admin/users" class="button button-secondary">View All Users</a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Admin</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Show only the 5 most recent users
                    $recentUsers = array_slice($users, 0, 5);
                    foreach ($recentUsers as $user) :
                        ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php
                            $name = trim($user['first_name'] . ' ' . $user['last_name']);
                            echo $name ? htmlspecialchars($name) : '<span class="text-muted">Not set</span>';
                            ?>
                        </td>
                        <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Books -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Recent Books</h2>
            <a href="/admin/books" class="button button-secondary">View All Books</a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Show only the 5 most recent books
                    $recentBooks = array_slice($books, 0, 5);
                    foreach ($recentBooks as $book) :
                        ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['id']); ?></td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td>
                            <?php echo $book['category_name'] ? htmlspecialchars($book['category_name']) : '<span class="text-muted">Uncategorized</span>'; ?>
                        </td>
                        <td>Br <?php echo number_format($book['price'], 2); ?></td>
                        <td>
                            <span class="<?php echo $book['stock'] <= 0 ? 'text-danger' : ''; ?>">
                                <?php echo htmlspecialchars($book['stock']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .dashboard-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .dashboard-card h3 {
        margin: 0 0 1rem 0;
        color: #4a5568;
        font-size: 1rem;
    }

    .dashboard-card .stat {
        margin: 0;
        font-size: 2rem;
        font-weight: bold;
        color: #2d3748;
    }

    .dashboard-section {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-header h2 {
        margin: 0;
        font-size: 1.25rem;
        color: #2d3748;
    }

    .text-muted {
        color: #718096;
        font-style: italic;
    }

    .text-danger {
        color: #e53e3e;
        font-weight: 500;
    }

    .table-responsive {
        overflow-x: auto;
        margin: 0 -1.5rem;
        padding: 0 1.5rem;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th,
    .admin-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .admin-table th {
        background-color: #f7fafc;
        font-weight: 600;
        color: #4a5568;
    }

    .admin-table tbody tr:hover {
        background-color: #f7fafc;
    }
</style> 