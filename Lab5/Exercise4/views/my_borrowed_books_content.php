<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please log in to view your borrowed books.";
    $_SESSION['message_type'] = "error";
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];

// Fetch books currently borrowed by the logged-in user
// Assuming a BookLoans table: loan_id, book_id, member_id (user_id), loan_date, return_date
$sql_borrowed = "SELECT b.book_id, b.title, b.author, bl.loan_date, bl.loan_id
                 FROM Books b
                 JOIN BookLoans bl ON b.book_id = bl.book_id
                 WHERE bl.member_id = ? AND bl.return_date IS NULL
                 ORDER BY bl.loan_date DESC";

$stmt_borrowed = $db->prepare($sql_borrowed);
$stmt_borrowed->bind_param("i", $current_user_id);
$stmt_borrowed->execute();
$result_borrowed = $stmt_borrowed->get_result();
?>
<section class="section section-my-borrowed-books">
    <div class="section-header">
        <h3><i class="fas fa-undo-alt"></i> My Borrowed Books</h3>
    </div>

    <?php if ($result_borrowed && $result_borrowed->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-heading"></i> Title</th>
                    <th><i class="fas fa-user-edit"></i> Author</th>
                    <th><i class="fas fa-calendar-plus"></i> Loaned On</th>
                    <th><i class="fas fa-undo"></i> Return Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($borrowed_book = $result_borrowed->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($borrowed_book['title']); ?></td>
                        <td><?php echo htmlspecialchars($borrowed_book['author']); ?></td>
                        <td><?php echo date("M d, Y", strtotime($borrowed_book['loan_date'])); ?></td>
                        <td class="actions-cell">
                            <form action="library_actions.php?action=return" method="POST">
                                <input type="hidden" name="loan_id" value="<?php echo $borrowed_book['loan_id']; ?>">
                                <input type="hidden" name="book_id" value="<?php echo $borrowed_book['book_id']; ?>"> 
                                <button type="submit" class="btn btn-danger btn-sm" title="Return this book">
                                    <i class="fas fa-minus-circle"></i> Return
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-state"><i class="fas fa-folder-open"></i> You have not borrowed any books currently. <a href="dashboard.php?view=available_books">Find a book to borrow!</a></p>
    <?php endif; ?>
    <?php $stmt_borrowed->close(); ?>
</section>