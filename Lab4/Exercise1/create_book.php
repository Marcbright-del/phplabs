<?php
// create_book.php

// Include the Book class definition
// It's good practice to use require_once to prevent multiple inclusions
require_once 'Book.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Collection</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Creating and Displaying Book Information</h1>

    <?php
    // --- Create an Object from the Class ---

    // Instantiate the Book class with specific values
    // This will call the __construct method in the Book class
    $book1 = new Book(
        "The Great Gatsby",
        "F. Scott Fitzgerald",
        1925,
        "Classic Fiction",
        10.99
    );

    // --- Display the values using a simple method (e.g., displayBookInfo()) ---
    $book1->displayBookInfo();

    echo "<hr>"; // Horizontal line for separation

    // You can create another book object
    $book2 = new Book(
        "To Kill a Mockingbird",
        "Harper Lee",
        1960,
        "Southern Gothic, Bildungsroman",
        7.99
    );
    $book2->displayBookInfo();

    echo "<hr>";

    // The __destruct method will be called for $book1 and $book2
    // automatically when the script finishes or when the objects are unset.
    ?>

    <p class="end-script-message">End of script.</p>

</body>
</html>
