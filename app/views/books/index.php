<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <style>
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 1rem 0;
        }
        .book-card {
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 8px;
        }
        .book-card h2 {
            margin-top: 0;
        }
        .book-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c5282;
        }
        .book-stock {
            color: #666;
            margin-bottom: 1rem;
        }
        .button-group {
            display: flex;
            gap: 0.5rem;
        }
        .button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .button-primary {
            background-color: #2c5282;
            color: white;
        }
        .button-secondary {
            background-color: #4a5568;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Available Books</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <div class="book-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><?php echo htmlspecialchars($book['description']); ?></p>
                <p class="book-price">$<?php echo number_format($book['price'], 2); ?></p>
                <p class="book-stock">
                    <?php if ($book['stock'] > 0): ?>
                        In Stock (<?php echo $book['stock']; ?> available)
                    <?php else: ?>
                        Out of Stock
                    <?php endif; ?>
                </p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="button-group">
                        <form action="/cart/add" method="POST" style="display: inline;">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" class="button button-primary" <?php echo $book['stock'] <= 0 ? 'disabled' : ''; ?>>
                                Add to Cart
                            </button>
                        </form>
                        <form action="/orders/create" method="POST" style="display: inline;">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" class="button button-secondary" <?php echo $book['stock'] <= 0 ? 'disabled' : ''; ?>>
                                Order Now
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <p><a href="/login" class="button button-primary">Login to Purchase</a></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html> 