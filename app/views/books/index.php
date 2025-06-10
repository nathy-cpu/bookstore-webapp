<?php
require_once __DIR__ . '/../../utils/View.php';
View::setLayout('base');
?>

<link rel="stylesheet" href="/public/css/books/main.css?v=<?php echo time(); ?>">

<div class="filters">
    <div class="search-box">
        <form action="/books" method="GET" class="search-form">
            <?php if (isset($_GET['category'])) : ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
            <?php endif; ?>
            <input type="text" 
                   name="search" 
                   placeholder="Search books..." 
                   value="<?php echo htmlspecialchars($searchQuery ?? ''); ?>"
                   class="search-input">
            <button type="submit" class="button button-primary">Search</button>
        </form>
    </div>
    <div class="category-filter">
        <a href="/books" class="category-link <?php echo !isset($_GET['category']) ? 'active' : ''; ?>">
            All Categories
        </a>
        <?php foreach ($categories as $category) : ?>
            <a href="/books?category=<?php echo $category['id']; ?>" 
               class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($selectedCategory) : ?>
    <h1>Books in <?php echo htmlspecialchars($selectedCategory['name']); ?></h1>
<?php else : ?>
    <h1>All Books</h1>
<?php endif; ?>

<?php if (empty($books)) : ?>
    <p class="no-results">No books found.</p>
<?php else : ?>
    <div class="book-grid">
        <?php foreach ($books as $book) : ?>
            <div class="book-card">
                <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p class="book-category">
                    <?php if ($book['category_name']) : ?>
                        <span class="category-tag">
                            <?php echo htmlspecialchars($book['category_name']); ?>
                        </span>
                    <?php endif; ?>
                </p>
                <p><?php
                    $shortDesc = strlen($book['description']) > 100 ?
                        substr($book['description'], 0, 100) . '...' :
                        $book['description'];
                    echo htmlspecialchars($shortDesc);
                ?></p>
                <p><a href="/books/<?php echo $book['id']; ?>" class="button button-secondary">View Details</a></p>
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
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
    .filters {
        margin-bottom: 2rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .search-box {
        margin-bottom: 1rem;
    }
    .search-form {
        display: flex;
        gap: 1rem;
    }
    .search-input {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 1rem;
    }
    .category-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .category-link {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        color: #4a5568;
        background: #edf2f7;
        transition: all 0.2s;
    }
    .category-link:hover {
        background: #e2e8f0;
    }
    .category-link.active {
        background: #2c5282;
        color: white;
    }
    .no-results {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        color: #4a5568;
    }
    .book-category {
        margin: 0.5rem 0;
    }
    .category-tag {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #ebf4ff;
        color: #2c5282;
        border-radius: 9999px;
        font-size: 0.875rem;
    }
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
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .book-card h2 {
        margin-top: 0;
        color: #2d3748;
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
</style> 