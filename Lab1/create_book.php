<?php
// Include the database connection file
// Make sure you have a db_connect.php file with your database connection details
include 'db_connect.php';

$message = ''; // Variable to store success or error messages

// Check if the form has been submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form using $_POST superglobal
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $price = $_POST['price'];

    error_log($genre);

    // Construct the SQL query
    $sql = "INSERT INTO Books (title, author, publication_year, genre, price) 
            VALUES ('$title', '$author', $year, '$genre', $price)";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $message = "New book added successfully!";
    } else {
        $message = "Error adding book: " . $conn->error;
    }
}

// Close the database connection
// This should ideally be done after all database operations are complete
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h2>Add New Book</h2>

        <?php
        // Display the message if it's set
        if ($message):
        ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="create_book.php">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>

            <label for="year">Publication Year:</label>
            <input type="number" id="year" name="year" required>

            <label for="genre">Genre:</label>
            <input type="text" name="genre" required>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>

            <input type="submit" value="Add Book">
        </form>

        <a href="read_books.php">View All Books</a>
    </div>
</body>
</html>
