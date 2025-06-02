<?php
require_once 'Loanable.php';

class Book implements Loanable {
    public int $book_id;
    public string $title;
    public string $author;
    public float $price;
    public ?string $genre;
    public ?int $year;
    public bool $is_borrowed;

    public function __construct(int $book_id, string $title, string $author, float $price, ?string $genre, ?int $year, bool $is_borrowed = false) {
        $this->book_id = $book_id;
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
        $this->genre = $genre;
        $this->year = $year;
        $this->is_borrowed = $is_borrowed;
    }

    public function getDetails(): string {
        return "ID: {$this->book_id}, Title: {$this->title}, Author: {$this->author}, Price: \${$this->price}";
    }

    public function borrowBook(int $memberId, mysqli $db): bool {
        if ($this->is_borrowed) {
            // echo "Book '{$this->title}' is already borrowed.\n";
            return false;
        }

        $db->begin_transaction();
        try {
            // Update book status
            $stmtBook = $db->prepare("UPDATE Books SET is_borrowed = TRUE WHERE book_id = ?");
            $stmtBook->bind_param("i", $this->book_id);
            $stmtBook->execute();

            // Record the loan
            $loan_date = date('Y-m-d');
            $stmtLoan = $db->prepare("INSERT INTO BookLoans (book_id, member_id, loan_date) VALUES (?, ?, ?)");
            $stmtLoan->bind_param("iis", $this->book_id, $memberId, $loan_date);
            $stmtLoan->execute();

            $db->commit();
            $this->is_borrowed = true;
            // echo "Book '{$this->title}' borrowed successfully by member ID {$memberId}.\n";
            return true;
        } catch (mysqli_sql_exception $exception) {
            $db->rollback();
            // echo "Error borrowing book: " . $exception->getMessage() . "\n";
            return false;
        }
    }

    public function returnBook(mysqli $db): bool {
        if (!$this->is_borrowed) {
            // echo "Book '{$this->title}' is not currently borrowed or already returned.\n";
            return false;
        }
        
        $db->begin_transaction();
        try {
            // Update book status
            $stmtBook = $db->prepare("UPDATE Books SET is_borrowed = FALSE WHERE book_id = ?");
            $stmtBook->bind_param("i", $this->book_id);
            $stmtBook->execute();

            // Update loan record
            $return_date = date('Y-m-d');
            // Find the latest open loan for this book and mark it as returned
            // It's safer to pass member_id or loan_id if multiple people could have borrowed it sequentially
            // For simplicity, we assume returning the most recent loan for this book_id
            $stmtLoan = $db->prepare("UPDATE BookLoans SET return_date = ? WHERE book_id = ? AND return_date IS NULL ORDER BY loan_date DESC LIMIT 1");
            $stmtLoan->bind_param("si", $return_date, $this->book_id);
            $stmtLoan->execute();
            
            if ($stmtLoan->affected_rows === 0) {
                // This might happen if the book was marked borrowed but no loan record was found (data inconsistency)
                // Or if somehow returnBook was called on a book not in BookLoans
                // For now, we proceed with un-borrowing the book, but this is a point for more robust error handling.
                // echo "Warning: No open loan record found to update for book ID {$this->book_id}, but book status updated.\n";
            }

            $db->commit();
            $this->is_borrowed = false;
            // echo "Book '{$this->title}' returned successfully.\n";
            return true;
        } catch (mysqli_sql_exception $exception) {
            $db->rollback();
            // echo "Error returning book: " . $exception->getMessage() . "\n";
            return false;
        }
    }
}
?>