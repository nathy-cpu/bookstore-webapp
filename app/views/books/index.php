<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
</head>
<body>
    <h1>Books</h1>
    <ul>
        <?php foreach ($books as $book): ?>
            <li><a href="/books/<?php echo $book['id']; ?>"><?php echo $book['title']; ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html> 