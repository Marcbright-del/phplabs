<?php
// Determine active link
$current_page_view = isset($_GET['view']) ? $_GET['view'] : 'home'; // Default view
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-book-reader logo-icon"></i>
        <h1>LibSys</h1>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php?view=home" class="<?php echo ($current_page_view === 'home') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
        </a>
        <a href="dashboard.php?view=available_books" class="<?php echo ($current_page_view === 'available_books') ? 'active' : ''; ?>"> 
            <i class="fas fa-book-open"></i> <span>Available Books</span> 
        </a>
        <a href="dashboard.php?view=my_borrowed_books" class="<?php echo ($current_page_view === 'my_borrowed_books') ? 'active' : ''; ?>"> 
            <i class="fas fa-history"></i> <span>My Borrowed Books</span> 
        </a>
         <a href="dashboard.php?view=manage_all_books" class="<?php echo ($current_page_view === 'manage_all_books' || $current_page_view === 'add_book' || $current_page_view === 'edit_book') ? 'active' : ''; ?>">
            <i class="fas fa-book-reader"></i> <span>Manage All Books (CRUD)</span> 
        </a>
        <a href="dashboard.php?view=view_users" class="<?php echo ($current_page_view === 'view_users') ? 'active' : ''; ?>"> 
            <i class="fas fa-users"></i> <span>Registered Users</span> 
        </a>
        <a href="dashboard.php?view=profile" class="<?php echo ($current_page_view === 'profile') ? 'active' : ''; ?>">
            <i class="fas fa-user-circle"></i> <span>Profile</span>
        </a>
        <!-- Add more links as needed -->
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php" class="btn btn-danger btn-sm btn-block" style="color:white;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <p>© <?php echo date("Y"); ?></p>
    </div>
</aside>