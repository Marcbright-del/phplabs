<?php
// Include the database connection file
include 'db_connect.php';

// SQL query to select all records from the Books table
$sql = "SELECT book_id, title, author, publication_year, genre, price FROM Books";
$result = $conn->query($sql); // Execute the query

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Books</title>
    <link rel="stylesheet" href="read_books_styles.css"> <!-- Link to the new CSS file -->
</head>
<body>
    <div class="container">
        <h2>Available Books</h2>

        <p><a href="create_book.php">Add New Book</a></p>

        <?php
        // Check if the query returned any rows
        if ($result->num_rows > 0) {
            // Start an HTML table to display the books
            echo "<table border='1'>"; // Added a simple border for visibility without CSS
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Title</th>";
            echo "<th>Author</th>";
            echo "<th>Year</th>";
            echo "<th>Genre</th>";
            echo "<th>Price</th>";
            echo "<th>Actions</th>"; // Column for Update/Delete links
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Loop through each row of the result set
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["book_id"] . "</td>";
                echo "<td>" . $row["title"] . "</td>";
                echo "<td>" . $row["author"] . "</td>";
                echo "<td>" . $row["publication_year"] . "</td>";
                echo "<td>" . $row["genre"] . "</td>";
                echo "<td>FCFA" . number_format($row["price"], 2) . "</td>"; // Format price
                echo "<td>";
                // Link to update page, passing the book_id in the URL
                echo "<a href='update_book.php?id=" . $row["book_id"] . "'>Edit</a> | ";
                // Link to delete script, passing the book_id in the URL
                // Added a simple JavaScript confirmation for safety
                echo "<a href='delete_book.php?id=" . $row["book_id"] . "' onclick='return confirm(\"Are you sure you want to delete this book?\");'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            // Message to display if no books are found
            echo "<p>No books found in the library.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>

