<?php

// Include the parent Product class
require_once 'Product.php';

// 2. Create a Child Class (File: Book.php)
// Inherit from the Product class using the 'extends' keyword
class Book extends Product {
    // Additional properties specific to books
    public $author;
    public $publication_year;
    public $genre;

    // Constructor to initialize both inherited and book-specific properties
    public function __construct($name, $price, $author, $year, $genre) {
        // Call the parent constructor using parent::__construct()
        // This initializes the $product_name and $product_price properties in the Product class
        parent::__construct($name, $price);

        // Initialize book-specific properties
        $this->author = $author;
        $this->publication_year = $year;
        $this->genre = $genre;
    }

    // Override the displayProduct() method
    // This method will provide a more specific display for a Book
    public function displayProduct() {
        // You can call the parent's method if you want to include its output
        // echo "--- Product Information ---<br>";
        // parent::displayProduct();
        // echo "--- Book Specific Information ---<br>";

        echo "Book Title: " . htmlspecialchars($this->product_name) . "<br>"; // Access inherited property
        echo "Author: " . htmlspecialchars($this->author) . "<br>";
        echo "Publication Year: " . htmlspecialchars($this->publication_year) . "<br>";
        echo "Genre: " . htmlspecialchars($this->genre) . "<br>";
        echo "Price: $" . htmlspecialchars(number_format($this->product_price, 2)) . "<br>"; // Access inherited property
    }

    // Optional: Getter methods for book-specific properties
    public function getAuthor() {
        return $this->author;
    }

    public function getPublicationYear() {
        return $this->publication_year;
    }

    public function getGenre() {
        return $this->genre;
    }
}

?>
