<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .button.edit {
            background-color: #2196F3;
        }
        .button.delete {
            background-color: #f44336;
        }
        .add-new {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <div class="section">
        <h2>Users</h2>
        <div class="add-new">
            <a href="/admin/users/add" class="button">Add New User</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['is_admin'] ? 'Yes' : 'No' ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td class="actions">
                        <a href="/admin/users/edit/<?= $user['id'] ?>" class="button edit">Edit</a>
                        <form action="/admin/users/delete/<?= $user['id'] ?>" method="POST" style="display: inline;">
                            <button type="submit" class="button delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Books</h2>
        <div class="add-new">
            <a href="/admin/books/add" class="button">Add New Book</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['id']) ?></td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td>$<?= number_format($book['price'], 2) ?></td>
                    <td><?= htmlspecialchars($book['stock']) ?></td>
                    <td class="actions">
                        <a href="/admin/books/edit/<?= $book['id'] ?>" class="button edit">Edit</a>
                        <form action="/admin/books/delete/<?= $book['id'] ?>" method="POST" style="display: inline;">
                            <button type="submit" class="button delete" onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        <a href="/books" class="button">Back to Store</a>
    </div>
</body>
</html> 