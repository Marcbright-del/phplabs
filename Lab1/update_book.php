<?php
// Include the database connection file
include 'db_connect.php';

$book_id = null;
$book = null;
$message = '';

// Check if a book ID is provided in the URL (GET request)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];

    // Prepare a statement to prevent SQL injection when fetching book data
    $stmt = $conn->prepare("SELECT book_id, title, author, publication_year, genre, price FROM Books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id); // 'i' indicates integer type
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a book with the given ID was found
    if ($result->num_rows == 1) {
        $book = $result->fetch_assoc(); // Fetch the book data
    } else {
        $message = "Book not found.";
    }
    $stmt->close(); // Close the statement
}

// Check if the form has been submitted (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the book ID from the hidden input field
    $book_id = $_POST['book_id'];

    // Get updated data from the form
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $price = $_POST['price'];

    // Construct the SQL query to update the book details
    $sql = "UPDATE Books 
            SET title = '$title', author = '$author', publication_year = $year, genre = '$genre', price = $price 
            WHERE book_id = $book_id";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $message = "Book updated successfully!";
        // Redirect back to the read_books page after successful update
        header("Location: read_books.php");
        exit(); // Stop further script execution
    } else {
        $message = "Error updating book: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h2>Update Book Information</h2>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($book): // Display the form only if book data was retrieved ?>
        <form method="POST" action="update_book.php">
            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>">

            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required><br><br>

            <label for="author">Author:</label><br>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required><br><br>

            <label for="year">Publication Year:</label><br>
            <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($book['publication_year']); ?>" required><br><br>

            <label for="genre">Genre:</label><br>
            <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" required><br><br>

            <label for="price">Price:</label><br>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($book['price']); ?>" required><br><br>

            <input type="submit" value="Update Book">
        </form>
    <?php elseif (!$message): // Show message if book not found and no other message is set ?>
         <p>Invalid book ID provided.</p>
    <?php endif; ?>

    <br>
    <a href="read_books.php">&larr; Back to Book List</a>

</body>
</html>

