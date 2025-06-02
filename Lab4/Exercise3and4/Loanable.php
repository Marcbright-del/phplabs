<?php
interface Loanable {
    public function borrowBook(int $memberId, mysqli $db): bool;
    public function returnBook(mysqli $db): bool; // Changed: memberId might not be needed for return if we use loan_id
}
?>