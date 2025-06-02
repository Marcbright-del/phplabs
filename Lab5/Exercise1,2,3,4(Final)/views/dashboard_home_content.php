<?php
// Fetch some stats for the dashboard
$total_books_res = $db->query("SELECT COUNT(*) as count FROM Books");
$total_books = $total_books_res->fetch_assoc()['count'];

$total_users_res = $db->query("SELECT COUNT(*) as count FROM users");
$total_users = $total_users_res->fetch_assoc()['count'];
?>
<section class="dashboard-overview section section-dashboard">
    <div class="stat-card">
        <i class="fas fa-book stat-icon icon-books"></i>
        <div>
            <h4>Total Books</h4>
            <p><?php echo $total_books; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-users stat-icon icon-members"></i>
        <div>
            <h4>Registered Users</h4>
            <p><?php echo $total_users; ?></p>
        </div>
    </div>
    <!-- Add more stat cards as needed -->
</section>
<section class="quick-actions section">
    <h3>Quick Actions</h3>
    <div class="action-buttons">
        <a href="dashboard.php?view=add_book" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add New Book</a>
        <a href="dashboard.php?view=manage_all_books" class="btn btn-secondary"><i class="fas fa-list-ul"></i> View All Books</a>
    </div>
</section>