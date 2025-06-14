



<header class="main-header">
    <div class="header-left">
        <button class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
        <h2 id="page-title">
            <?php
            // Dynamically set page title based on view
            $view_title = "Dashboard"; // Default
            if (isset($_GET['view'])) {
                switch ($_GET['view']) {
                    case 'home': $view_title = "Overview"; break;
                    case 'available_books': $view_title = "Available Books"; break; // New/Updated
                    case 'my_borrowed_books': $view_title = "My Borrowed Books"; break; // New
                    case 'manage_all_books': $view_title = "Book Inventory (Admin)"; break; // Admin 
                    case 'add_book': $view_title = "Add New Book"; break;
                    case 'view_users': $view_title = "Registered Users"; break;
                    case 'edit_book': $view_title = "Edit Book Details"; break;
                    case 'profile': $view_title = "User Profile"; break;
                }
            }
            echo $view_title;
            ?>
        </h2>
    </div>
    <div class="user-profile">
        <div class="user-profile">
    <div class="user-avatar">
        <?php 
        $username = $_SESSION["username"] ?? 'User';
        $firstLetter = strtoupper(substr($username, 0, 1));
        echo htmlspecialchars($firstLetter); 
        ?>
    </div>
       
    </div>
    
</header>
