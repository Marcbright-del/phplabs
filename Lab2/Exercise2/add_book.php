<?php
require_once 'db_config.php'; // Includes session_start()

// Fetch authors for the dropdown
$authors_result = mysqli_query($conn, "SELECT author_id, author_name FROM Authors ORDER BY author_name ASC");
$authors = [];
if ($authors_result) {
    while ($row = mysqli_fetch_assoc($authors_result)) {
        $authors[] = $row;
    }
} else {
    // Handle error if authors can't be fetched
    echo "Error fetching authors: " . mysqli_error($conn);
    // You might want to die or display a more user-friendly message
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - Library System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Library Management System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="add_book.php" class="active">Add Book</a></li>
            <li><a href="view_books.php">View Books</a></li>
            <!-- You can add more links here, e.g., Add Author -->
        </ul>
    </nav>
    <div class="container">
        <h2>Add a New Book</h2>

        <?php
        // Display messages from process_book.php
        if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type']; ?>">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>

        <form action="process_book.php" method="POST">
            <div>
                <label for="book_title">Book Title:</label>
                <input type="text" id="book_title" name="book_title" required>
            </div>

            <div>
                <label for="author_id">Author:</label>
                <select id="author_id" name="author_id" required>
                    <option value="">-- Select Author --</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?php echo htmlspecialchars($author['author_id']); ?>">
                            <?php echo htmlspecialchars($author['author_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- You could add an "Add New Author" button here that opens a modal or goes to another page -->

            <div>
                <label for="genre">Genre:</label>
                <input type="text" id="genre" name="genre">
            </div>

            <div>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>

            <div>
                <input type="submit" value="Add Book">
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); // Close connection ?>