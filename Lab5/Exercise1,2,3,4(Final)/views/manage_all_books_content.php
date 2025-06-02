<?php
// File: views/manage_all_books_content.php
// This page is for full CRUD management of all books.
// Add admin role check here if you have roles implemented:
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     $_SESSION['message'] = "Access Denied: You do not have permission to manage books.";
//     $_SESSION['message_type'] = "error";
//     echo "<script>window.location.href='dashboard.php?view=home';</script>";
//     exit;
// }


$search_term = isset($_GET['search_all_books']) ? $db->real_escape_string($_GET['search_all_books']) : '';
$sql = "SELECT b.book_id, b.title, b.author, b.price, b.genre, b.year, 
               CASE 
                   WHEN bl.book_id IS NOT NULL THEN 'Borrowed'
                   ELSE 'Available'
               END as loan_status
        FROM Books b
        LEFT JOIN (
            SELECT book_id FROM BookLoans WHERE return_date IS NULL GROUP BY book_id
        ) AS bl ON b.book_id = bl.book_id";

if (!empty($search_term)) {
    $sql .= " WHERE b.title LIKE '%$search_term%' OR b.author LIKE '%$search_term%' OR b.genre LIKE '%$search_term%'";
}
$sql .= " ORDER BY b.title ASC";
$result = $db->query($sql);
?>
<section class="section section-manage-all-books">
    <div class="section-header">
        <h3><i class="fas fa-book-medical"></i> Manage All Books (Full CRUD)</h3>
        <a href="dashboard.php?view=add_book" class="btn btn-success"><i class="fas fa-plus"></i> Add New Book</a>
    </div>

    <form method="GET" action="dashboard.php" class="filter-form mb-3">
        <input type="hidden" name="view" value="manage_all_books">
        <div class="form-group-inline">
            <input type="text" name="search_all_books" class="form-control" placeholder="Search all books by title, author, genre..." value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if(!empty($search_term)): ?>
                 <a href="dashboard.php?view=manage_all_books" class="btn btn-outline-secondary">Clear Search</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><i class="fas fa-heading"></i> Title</th>
                    <th><i class="fas fa-user-edit"></i> Author</th>
                    <th><i class="fas fa-dollar-sign"></i> Price</th>
                    <th><i class="fas fa-tags"></i> Genre</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-cogs"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $book['book_id']; ?></td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td>$<?php echo htmlspecialchars($book['price']); ?></td>
                        <td><?php echo htmlspecialchars($book['genre']); ?></td>
                        <td>
                            <span class="status-badge <?php echo ($book['loan_status'] == 'Borrowed') ? 'status-borrowed' : 'status-available'; ?>">
                                <?php echo htmlspecialchars($book['loan_status']); ?>
                            </span>
                        </td>
                        <td class="actions-cell">
                            <a href="dashboard.php?view=edit_book&id=<?php echo $book['book_id']; ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                            <form action="book_actions.php" method="POST" class="d-inline" onsubmit="return confirmDelete('<?php echo htmlspecialchars(addslashes($book['title'])); ?>');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $book['book_id']; ?>">
                                <button type="submit" class="btn btn-danger" title="Delete">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-state"><i class="fas fa-book-dead"></i> No books found in the inventory. <?php if(empty($search_term)) echo '<a href="dashboard.php?view=add_book">Add the first one!</a>'; ?></p>
    <?php endif; ?>
</section>

<script>
function confirmDelete(bookTitle) {
    return confirm(`Are you sure you want to PERMANENTLY DELETE this book: '${bookTitle}'? This action cannot be undone.`);
}
</script>