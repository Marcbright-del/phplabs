<?php

// Include the class files
require_once 'Product.php';
require_once 'Book.php';

// 3. Test Inheritance (File: test_inheritance.php)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inheritance Test</title>
    <link rel="stylesheet" href="inheritance_styles.css">
</head>
<body>

    <h2>Testing Product Class</h2>

    <?php
    // Create an object of the Product class
    $genericProduct = new Product("Laptop", 1200.50);

    // Call the displayProduct() method of the Product object
    $genericProduct->displayProduct();
    ?>

    <br> <h2>Testing Book Class (Inheritance)</h2>

    <?php
    // Create an object of the Book class
    // Pass values for both inherited and book-specific properties
    $myBook = new Book(
        "The Hitchhiker's Guide to the Galaxy", // Product Name (Title)
        10.99,                               // Product Price
        "Douglas Adams",                     // Book Author
        1979,                                // Book Publication Year
        "Science Fiction"                    // Book Genre
    );

    // Call the displayProduct() method of the Book object
    // This will call the overridden method in the Book class
    $myBook->displayProduct();
    ?>

    <br>

    <h2>Accessing Properties</h2>

    <?php
    // You can also access inherited properties and methods through the Book object
    echo "Accessing inherited property (Name) from Book object: " . htmlspecialchars($myBook->getProductName()) . "<br>";
    echo "Accessing inherited property (Price) from Book object: $" . htmlspecialchars(number_format($myBook->getProductPrice(), 2)) . "<br>";

    // Accessing book-specific properties
    echo "Accessing book-specific property (Author) from Book object: " . htmlspecialchars($myBook->author) . "<br>"; // Public property
    echo "Accessing book-specific property (Genre) from Book object: " . htmlspecialchars($myBook->getGenre()) . "<br>"; // Using getter
    ?>

</body>
</html>
