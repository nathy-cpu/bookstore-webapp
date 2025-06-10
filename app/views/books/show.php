<?php
require_once __DIR__ . '/../../utils/View.php';
View::setLayout('base');
?>

<link rel="stylesheet" href="/public/css/books/main.css?v=<?php echo time(); ?>">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?></title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .book-details {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 2rem auto;
        }
        .book-title {
            color: #2d3748;
            margin-bottom: 1rem;
        }
        .book-meta {
            color: #4a5568;
            margin-bottom: 2rem;
        }
        .book-description {
            line-height: 1.6;
            color: #2d3748;
            margin-bottom: 2rem;
        }
        .book-price {
            font-size: 1.5rem;
            color: #2c5282;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .book-stock {
            color: #718096;
            margin-bottom: 2rem;
        }
        .button {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 1rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .button-primary {
            background-color: #2c5282;
            color: white;
        }
        .button-primary:hover {
            background-color: #2a4365;
        }
        .button-secondary {
            background-color: #4a5568;
            color: white;
        }
        .button-secondary:hover {
            background-color: #2d3748;
        }
        .button[disabled] {
            background-color: #cbd5e0;
            cursor: not-allowed;
        }
        .button-group {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="book-details">
            <h1 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h1>
            <div class="book-meta">
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <?php if ($book['isbn']) : ?>
                    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
                <?php endif; ?>
                <?php if ($book['category_name']) : ?>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($book['category_name']); ?></p>
                <?php endif; ?>
            </div>
            <div class="book-description">
                <?php echo nl2br(htmlspecialchars($book['description'])); ?>
            </div>
            <p class="book-price">Br <?php echo number_format($book['price'], 2); ?></p>
            <p class="book-stock">
                <?php if ($book['stock'] > 0) : ?>
                    In Stock (<?php echo $book['stock']; ?> available)
                <?php else : ?>
                    Out of Stock
                <?php endif; ?>
            </p>
            <?php if (isset($_SESSION['user_id'])) : ?>
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
            <?php else : ?>
                <p><a href="/login" class="button button-primary">Login to Purchase</a></p>
            <?php endif; ?>
            <p style="margin-top: 2rem;">
                <a href="/books" class="button button-secondary">Back to Books</a>
            </p>
        </div>
    </div>
</body>
</html> 