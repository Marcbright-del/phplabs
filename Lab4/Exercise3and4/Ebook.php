<?php
require_once 'Book.php';
require_once 'Discountable.php';

class Ebook extends Book implements Discountable {
    public string $file_format; // e.g., PDF, EPUB
    private float $discount_percentage;

    public function __construct(int $book_id, string $title, string $author, float $price, ?string $genre, ?int $year, bool $is_borrowed = false, string $file_format = 'PDF', float $discount_percentage = 0.10) {
        parent::__construct($book_id, $title, $author, $price, $genre, $year, $is_borrowed);
        $this->file_format = $file_format;
        $this->discount_percentage = $discount_percentage; // 10% discount by default
    }

    public function getDetails(): string {
        return parent::getDetails() . ", Format: {$this->file_format}, Discounted Price: $" . $this->getDiscountedPrice();
    }

    public function download(): void {
        echo "Downloading eBook '{$this->title}' in {$this->file_format} format...\n";
        // In a real app, this would trigger a file download
    }

    public function getDiscountedPrice(): float {
        return round($this->price * (1 - $this->discount_percentage), 2);
    }

    // Ebooks might have different borrowing logic (e.g., unlimited copies, time-limited access)
    // For this exercise, we'll inherit the Book's borrow/return logic,
    // but acknowledge it could be overridden.
    // If Ebooks are not "borrowed" in the sense of limited availability,
    // the is_borrowed flag and borrowBook/returnBook might behave differently or not be used.
    // For now, we assume they can be "borrowed" once like physical books based on the problem structure.
}
?>