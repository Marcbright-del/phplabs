<?php
require_once 'db_config.php'; // Includes session_start()

// Fetch all books with author names
$sql = "SELECT b.book_id, b.book_title, a.author_name, b.genre, b.price
        FROM Books b
        INNER JOIN Authors a ON b.author_id = a.author_id
        ORDER BY b.book_id ASC";

$result = mysqli_query($conn, $sql);
$books = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
} else {
    $error_message = "Error fetching books: " . mysqli_error($conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books - Library System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Library Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="add_book.php">Add Book</a></li>
            <li><a href="view_books.php" class="active">View Books</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>All Books</h2>

        <?php if (isset($error_message)): ?>
            <p class="message error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($books)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Price</th>
                        <!-- Add Actions column if needed (Edit/Delete) -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['book_title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author_name']); ?></td>
                            <td><?php echo htmlspecialchars($book['genre'] ? $book['genre'] : 'N/A'); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($book['price'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (!isset($error_message)): ?>
            <p>No books found in the library. <a href="add_book.php">Add a new book</a>.</p>
        <?php endif; ?>
        <br>
        <a href="add_book.php" class="button">Add Another Book</a>
    </div>
</body>
</html>
<?php mysqli_close($conn); // Close connection ?>