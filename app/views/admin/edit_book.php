<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
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
        .button.cancel {
            background-color: #f44336;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Book</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/books/edit/<?= htmlspecialchars($book['id']) ?>">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required 
                       value="<?= htmlspecialchars($book['title']) ?>">
            </div>

            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required 
                       value="<?= htmlspecialchars($book['author']) ?>">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?= htmlspecialchars($book['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required 
                       value="<?= htmlspecialchars($book['price']) ?>">
            </div>

            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" min="0" required 
                       value="<?= htmlspecialchars($book['stock']) ?>">
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="button">Save Changes</button>
                <a href="/admin" class="button cancel" style="margin-left: 10px;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html> 