<?php
class Member {
    public int $member_id;
    public string $name;
    public string $email;
    public string $membership_date; // Store as Y-m-d string

    public function __construct(int $member_id, string $name, string $email, string $membership_date) {
        $this->member_id = $member_id;
        $this->name = $name;
        $this->email = $email;
        $this->membership_date = $membership_date;
    }

    public function getMemberDetails(): string {
        return "Member ID: {$this->member_id}, Name: {$this->name}, Email: {$this->email}, Member Since: {$this->membership_date}";
    }

    public function viewBorrowedBooks(mysqli $db): array {
        $borrowedBooks = [];
        $sql = "SELECT b.book_id, b.title, b.author, b.price, b.genre, b.year, b.is_ebook, bl.loan_date
                FROM Books b
                JOIN BookLoans bl ON b.book_id = bl.book_id
                WHERE bl.member_id = ? AND bl.return_date IS NULL
                ORDER BY bl.loan_date DESC";
        
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            // echo "Error preparing statement: " . $db->error . "\n";
            return $borrowedBooks;
        }
        $stmt->bind_param("i", $this->member_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            // We can return simplified info or full Book/Ebook objects
            // For simplicity here, returning an array of associative arrays
            $borrowedBooks[] = [
                'book_id' => $row['book_id'],
                'title' => $row['title'],
                'author' => $row['author'],
                'loan_date' => $row['loan_date'],
                'is_ebook' => (bool)$row['is_ebook'] // Cast to boolean
            ];
        }
        $stmt->close();
        return $borrowedBooks;
    }
}
?>