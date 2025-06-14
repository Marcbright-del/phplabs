<?php
// Include the database connection file
include 'db_connect.php';

$message = '';

// Check if a book ID is provided in the URL (GET request)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];

    // Prepare a DELETE statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM Books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id); // 'i' indicates integer type

    // Execute the delete statement
    if ($stmt->execute()) {
        $message = "Book deleted successfully!";
        // Redirect back to the read_books page after successful deletion
        header("Location: read_books.php");
        exit(); // Stop further script execution
    } else {
        $message = "Error deleting book: " . $conn->error;
    }
    $stmt->close(); // Close the statement
} else {
    // Message if no book ID was provided
    $message = "No book ID specified for deletion.";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Book Status</title>
    
</head>
<body>

    <h2>Delete Book Status</h2>

    <p><?php echo $message; ?></p>

    <br>
    <a href="read_books.php">&larr; Back to Book List</a>

</body>
</html>
