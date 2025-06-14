<?php
// This is views/available_books_content.php

// Fetch all users for the borrow dropdown
$members_list_stmt = $db->query("SELECT id, username FROM users ORDER BY id ASC");
$members_for_borrow = [];
if ($members_list_stmt) {
    while ($member_row = $members_list_stmt->fetch_assoc()) {
        $members_for_borrow[] = $member_row;
    }
}


$search_term = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';
// For available books, we need to join with a loans table to see what's NOT borrowed or IS returned
// Let's assume a simple `is_borrowed` flag on the Books table for now,
// or a more complex query if you have a BookLoans table.
// For this example, let's assume a BookLoans table with book_id, member_id, loan_date, return_date
// A book is available if it's not in BookLoans with a NULL return_date.

// More robust query for available books:
$sql = "SELECT b.book_id, b.title, b.author, b.price, b.genre, b.year
        FROM Books b
        LEFT JOIN (
            SELECT book_id FROM BookLoans WHERE return_date IS NULL GROUP BY book_id
        ) AS borrowed_books ON b.book_id = borrowed_books.book_id
        WHERE borrowed_books.book_id IS NULL"; // Only books NOT in the borrowed_books subquery

if (!empty($search_term)) {
    $sql .= " AND (b.title LIKE '%$search_term%' OR b.author LIKE '%$search_term%' OR b.genre LIKE '%$search_term%')";
}
$sql .= " ORDER BY b.title ASC";
$result = $db->query($sql);

// If you are using a simple `is_borrowed` flag on the Books table:
/*
$sql = "SELECT book_id, title, author, price, genre, year FROM Books WHERE is_borrowed = FALSE";
if (!empty($search_term)) {
    $sql .= " AND (title LIKE '%$search_term%' OR author LIKE '%$search_term%' OR genre LIKE '%$search_term%')";
}
$sql .= " ORDER BY title ASC";
$result = $db->query($sql);
*/
?>
<section class="section section-available-books">
    <div class="section-header">
        <h3><i class="fas fa-book-open"></i> Available Books for Loan</h3>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): // Example admin check ?>
            <a href="dashboard.php?view=add_book" class="btn btn-success"><i class="fas fa-plus"></i> Add Book</a>
        <?php endif; ?>
    </div>

    <form method="GET" action="dashboard.php" class="filter-form mb-3">
        <input type="hidden" name="view" value="available_books">
        <div class="form-group-inline">
            <input type="text" name="search" class="form-control" placeholder="Search available books..." value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(!empty($search_term)): ?>
                 <a href="dashboard.php?view=available_books" class="btn btn-outline-secondary">Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-heading"></i> Title</th>
                    <th><i class="fas fa-user-edit"></i> Author</th>
                    <th><i class="fas fa-tags"></i> Genre</th>
                    <th><i class="fas fa-calendar-alt"></i> Year</th>
                    <th><i class="fas fa-hand-holding-heart"></i> Borrow Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['title'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($book['author'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($book['genre'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($book['year'] ?? ''); ?></td>
                        <td class="actions-cell">
                            <form action="library_actions.php?action=borrow" method="POST" class="form-inline-borrow">
                                <input type="hidden" name="book_id" value="<?php echo $book['book_id'] ?? ''; ?>">
                                <input type="hidden" name="borrowing_user_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>"> 
                                <button type="submit" class="btn btn-success btn-sm" title="Borrow this book">
                                    <i class="fas fa-plus-circle"></i> Borrow
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-state"><i class="fas fa-book-dead"></i> No books currently available for loan. <?php if(empty($search_term)) echo 'Check back later!'; ?></p>
    <?php endif; ?>
</section>