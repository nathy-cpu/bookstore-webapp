<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
</head>
<body>
    <h1>Create Order</h1>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="/orders/create">
        <label for="book_id">Book ID:</label>
        <input type="number" id="book_id" name="book_id" required>
        <button type="submit">Create Order</button>
    </form>
    <a href="/orders">Back to Orders</a>
</body>
</html> 