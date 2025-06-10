<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Bookstore</title>
</head>
<body>
    <div class="hero">
        <h1>Welcome to Our Bookstore</h1>
        <p>Discover your next favorite book from our carefully curated collection</p>
        <a href="/books" class="button">Browse Books</a>
    </div>

    <div class="features">
        <div class="feature">
            <div class="feature-icon">📚</div>
            <h3>Wide Selection</h3>
            <p>Browse through our extensive collection of books across various genres</p>
        </div>
        <div class="feature">
            <div class="feature-icon">🚚</div>
            <h3>Fast Delivery</h3>
            <p>Get your books delivered right to your doorstep</p>
        </div>
        <div class="feature">
            <div class="feature-icon">💰</div>
            <h3>Best Prices</h3>
            <p>Enjoy competitive prices on all our books</p>
        </div>
    </div>

    <div class="featured-books">
        <h2>Featured Books</h2>
        <div class="book-grid">
            <?php 
            require_once __DIR__ . '/../models/Book.php';
            $bookModel = new Book();
            $featuredBooks = array_slice($bookModel->getAll(), 0, 3); // Get first 3 books
            foreach ($featuredBooks as $book): 
            ?>
                <div class="book-card">
                    <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p>by <?php echo htmlspecialchars($book['author']); ?></p>
                    <p class="book-price">$<?php echo number_format($book['price'], 2); ?></p>
                    <?php if ($book['stock'] > 0): ?>
                        <p>In Stock</p>
                    <?php else: ?>
                        <p>Out of Stock</p>
                    <?php endif; ?>
                    <a href="/books" class="button">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="view-all text-center mt-2">
            <a href="/books" class="button">View All Books</a>
        </div>
    </div>
</body>
</html> 