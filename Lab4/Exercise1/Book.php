<?php
// Book.php

/**
 * Class Book
 * Represents a book with properties like title, author, publication year, genre, and price.
 * It includes a constructor to initialize these properties and a method to display book information.
 */
class Book {
    // Properties of the Book class
    public string $title;
    public string $author;
    public int $publication_year;
    public string $genre;
    public float $price;

    /**
     * Constructor for the Book class.
     * Initializes the properties of a new Book object.
     *
     * @param string $title The title of the book.
     * @param string $author The author of the book.
     * @param int $publication_year The year the book was published.
     * @param string $genre The genre of the book.
     * @param float $price The price of the book.
     */
    public function __construct(string $title, string $author, int $publication_year, string $genre, float $price) {
        // Assign the provided values to the object's properties
        $this->title = $title;
        $this->author = $author;
        $this->publication_year = $publication_year;
        $this->genre = $genre;
        $this->price = $price;
        // Output a system message indicating object creation
        echo "<p class='system-message'>Book object for '" . htmlspecialchars($this->title) . "' created.</p>";
    }

    /**
     * Displays the information of the book using HTML structure for styling.
     * Outputs the book's title, author, publication year, genre, and price.
     */
    public function displayBookInfo(): void {
        // Start of the book container div
        echo "<div class='book-container'>";
        echo "<h2>" . htmlspecialchars($this->title) . "</h2>"; // Book title as a heading
        echo "<p><strong>Author:</strong> " . htmlspecialchars($this->author) . "</p>";
        echo "<p><strong>Publication Year:</strong> " . $this->publication_year . "</p>";
        echo "<p><strong>Genre:</strong> " . htmlspecialchars($this->genre) . "</p>";
        echo "<p><strong>Price:</strong> $" . number_format($this->price, 2) . "</p>";
        // End of the book container div
        echo "</div>";
    }

    /**
     * Destructor for the Book class.
     * Called when the object is no longer referenced or when the script ends.
     * Can be used for cleanup tasks.
     */
    public function __destruct() {
        // Output a system message indicating object destruction
        echo "<p class='system-message'>Book object for '" . htmlspecialchars($this->title) . "' is being destroyed.</p>";
    }
}
?>
