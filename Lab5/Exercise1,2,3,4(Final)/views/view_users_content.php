<?php
// For a real application, you'd add an admin check here:
// if (!isAdminUser()) { // Assuming you have a function like isAdminUser()
//     $_SESSION['message'] = "You do not have permission to view this page.";
//     $_SESSION['message_type'] = "error";
//     echo "<script>window.location.href='dashboard.php?view=home';</script>"; // Redirect via JS if header already sent
//     exit;
// }

$search_user_term = isset($_GET['search_user']) ? $db->real_escape_string($_GET['search_user']) : '';
$sql_users = "SELECT id, username, email, created_at, google_id FROM users";
if (!empty($search_user_term)) {
    $sql_users .= " WHERE username LIKE '%$search_user_term%' OR email LIKE '%$search_user_term%'";
}
$sql_users .= " ORDER BY username ASC";
$result_users = $db->query($sql_users);
?>
<section class="section section-view-users">
    <div class="section-header">
        <h3><i class="fas fa-users-cog"></i> Registered Users List</h3>
        <!-- Optionally, add a button to "Add User" if you implement admin user creation -->
    </div>

    <form method="GET" action="dashboard.php" class="filter-form mb-3">
        <input type="hidden" name="view" value="view_users">
        <div class="form-group-inline">
            <input type="text" name="search_user" class="form-control" placeholder="Search by username or email..." value="<?php echo htmlspecialchars($search_user_term); ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search Users</button>
            <?php if(!empty($search_user_term)): ?>
                 <a href="dashboard.php?view=view_users" class="btn btn-outline-secondary">Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($result_users && $result_users->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-id-badge"></i> ID</th>
                    <th><i class="fas fa-user"></i> Username</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-calendar-check"></i> Registered On</th>
                    <th><i class="fab fa-google"></i> Google Linked</th>
                    <th><i class="fas fa-cogs"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result_users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo date("M d, Y H:i", strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if (!empty($user['google_id'])): ?>
                                <span class="status-badge status-linked" title="Linked with Google"><i class="fas fa-check-circle"></i> Yes</span>
                            <?php else: ?>
                                <span class="status-badge status-not-linked" title="Not linked with Google"><i class="fas fa-times-circle"></i> No</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions-cell">
                            <!-- Add actions like "Edit User", "Delete User" (with admin checks) here -->
                            <a href="user_actions.php?action=delete_user&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" title="Delete User" onclick="return confirm('Are you sure?');"><i class="fas fa-user-times"></i></a> 
                            <button class="btn btn-info btn-sm" onclick="alert('View details for user ID <?php echo $user['id']; ?> - implement details page/modal.')" title="View Details"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-state"><i class="fas fa-user-slash"></i> No users found. <?php if(empty($search_user_term)) echo 'The user list is currently empty.'; ?></p>
    <?php endif; ?>
</section>