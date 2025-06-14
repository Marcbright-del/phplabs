<?php
session_start(); // For flash messages

require_once 'dbconfig.php';
require_once 'Book.php';
require_once 'Ebook.php';
require_once 'Member.php';

$message = '';
$message_type = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Determine the current view
$current_view = isset($_GET['view']) ? $_GET['view'] : 'dashboard'; // Default to dashboard overview

// --- Handle Actions (common for all views or specific to a view) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_view_redirect = $current_view; // By default, redirect back to the current view

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'borrow' && isset($_POST['book_id']) && isset($_POST['member_id'])) {
            // ... (Borrow logic - same as before) ...
            // Ensure $book_object is instantiated
            $book_id = (int)$_POST['book_id'];
            $member_id = (int)$_POST['member_id'];
            $stmt = $db->prepare("SELECT * FROM Books WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $book_data = $result->fetch_assoc();
            $stmt->close();

            if ($book_data) {
                $book_object = $book_data['is_ebook'] ?
                    new Ebook($book_data['book_id'], $book_data['title'], $book_data['author'], $book_data['price'], $book_data['genre'], $book_data['year'], $book_data['is_borrowed']) :
                    new Book($book_data['book_id'], $book_data['title'], $book_data['author'], $book_data['price'], $book_data['genre'], $book_data['year'], $book_data['is_borrowed']);

                if ($book_object->borrowBook($member_id, $db)) {
                    $_SESSION['message'] = "Book '{$book_object->title}' borrowed successfully!";
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = "Failed to borrow book '{$book_object->title}'. It might already be borrowed or an error occurred.";
                    $_SESSION['message_type'] = 'error';
                }
            } else {
                $_SESSION['message'] = "Book not found.";
                $_SESSION['message_type'] = 'error';
            }
            $action_view_redirect = 'available_books'; // Redirect to books view after borrowing

        } elseif ($action === 'return' && isset($_POST['book_id'])) {
            // ... (Return logic - same as before) ...
            $book_id = (int)$_POST['book_id'];
             $stmt = $db->prepare("SELECT * FROM Books WHERE book_id = ?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $book_data = $result->fetch_assoc();
            $stmt->close();
            if ($book_data) {
                 $book_object = $book_data['is_ebook'] ?
                    new Ebook($book_data['book_id'], $book_data['title'], $book_data['author'], $book_data['price'], $book_data['genre'], $book_data['year'], $book_data['is_borrowed']) :
                    new Book($book_data['book_id'], $book_data['title'], $book_data['author'], $book_data['price'], $book_data['genre'], $book_data['year'], $book_data['is_borrowed']);
                $book_object->is_borrowed = true; // Assume it is borrowed if a return action is triggered

                if ($book_object->returnBook($db)) {
                    $_SESSION['message'] = "Book '{$book_object->title}' returned successfully!";
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = "Failed to return book '{$book_object->title}'.";
                    $_SESSION['message_type'] = 'error';
                }
            } else {
                $_SESSION['message'] = "Book not found for return.";
                $_SESSION['message_type'] = 'error';
            }
            // If returning from a specific member's view, redirect there
            $action_view_redirect = 'borrowed_books';
            if (isset($_POST['selected_member_id_return'])) {
                 $action_view_redirect .= "&selected_member_id=" . $_POST['selected_member_id_return'];
            }


        } elseif ($action === 'add_member' && isset($_POST['member_name']) && isset($_POST['member_email'])) {
            // ... (Add member logic - same as before) ...
            $name = trim($_POST['member_name']);
            $email = trim($_POST['member_email']);
            $membership_date = date('Y-m-d');
            if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $stmt = $db->prepare("INSERT INTO Members (name, email, membership_date) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $membership_date);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Member '{$name}' added successfully!";
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = "Error adding member: " . $db->error;
                    $_SESSION['message_type'] = 'error';
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "Invalid member name or email.";
                $_SESSION['message_type'] = 'error';
            }
            $action_view_redirect = 'manage_members';

        } elseif ($action === 'add_book' && isset($_POST['book_title']) && isset($_POST['book_author']) && isset($_POST['book_price'])) {
            // ... (Add book logic - same as before) ...
            $title = trim($_POST['book_title']);
            $author = trim($_POST['book_author']);
            $price = (float)$_POST['book_price'];
            $genre = isset($_POST['book_genre']) ? trim($_POST['book_genre']) : null;
            $year = isset($_POST['book_year']) && !empty($_POST['book_year']) ? (int)$_POST['book_year'] : null;
            $is_ebook = isset($_POST['is_ebook']) ? 1 : 0;
            if (!empty($title) && !empty($author) && $price > 0) {
                $stmt = $db->prepare("INSERT INTO Books (title, author, price, genre, year, is_ebook) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdssi", $title, $author, $price, $genre, $year, $is_ebook);
                 if ($stmt->execute()) {
                    $_SESSION['message'] = "Book '{$title}' added successfully!";
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = "Error adding book: " . $db->error;
                    $_SESSION['message_type'] = 'error';
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "Invalid book details (Title, Author, Price are required).";
                $_SESSION['message_type'] = 'error';
            }
            $action_view_redirect = 'manage_books';
        }
         // Check if $action_view_redirect contains query parameters already
        $redirect_url = "library_test.php";
        if (strpos($action_view_redirect, '&') !== false || strpos($action_view_redirect, '=') !== false && strpos($action_view_redirect, '?') === false) {
             // This means $action_view_redirect might be like 'borrowed_books&selected_member_id=1'
             // or just 'borrowed_books' and we need to add '?'
            if (strpos($action_view_redirect, '=') !== false) { // it has params
                $redirect_url .= "?view=" . $action_view_redirect;
            } else { // it's just a view name
                 $redirect_url .= "?view=" . $action_view_redirect;
            }
        } else {
             $redirect_url .= "?view=" . $action_view_redirect;
        }


        header("Location: " . $redirect_url);
        exit;
    }
}


// --- Fetch Data (can be conditional based on view for optimization) ---
$available_books = [];
$members_list = [];
$borrowed_by_member = [];
$selected_member_id = null;
$selected_member_name = "Select a member";
$all_books_for_management = []; // For manage_books view

// Always fetch members list for dropdowns
$result_members = $db->query("SELECT * FROM Members ORDER BY name");
if ($result_members) {
    while ($row = $result_members->fetch_assoc()) {
        $members_list[] = new Member($row['member_id'], $row['name'], $row['email'], $row['membership_date']);
    }
}

// Fetch data based on current view
if ($current_view === 'available_books' || $current_view === 'dashboard') {
    $result_avail_books = $db->query("SELECT * FROM Books WHERE is_borrowed = FALSE ORDER BY title");
    if ($result_avail_books) {
        while ($row = $result_avail_books->fetch_assoc()) {
            $available_books[] = $row['is_ebook'] ?
                new Ebook($row['book_id'], $row['title'], $row['author'], $row['price'], $row['genre'], $row['year'], $row['is_borrowed']) :
                new Book($row['book_id'], $row['title'], $row['author'], $row['price'], $row['genre'], $row['year'], $row['is_borrowed']);
        }
    }
}

if ($current_view === 'manage_books' || $current_view === 'dashboard') { // Fetch all books for management view
    $result_all_books = $db->query("SELECT * FROM Books ORDER BY title");
    if ($result_all_books) {
        while ($row = $result_all_books->fetch_assoc()) {
            $all_books_for_management[] = $row['is_ebook'] ?
                new Ebook($row['book_id'], $row['title'], $row['author'], $row['price'], $row['genre'], $row['year'], $row['is_borrowed']) :
                new Book($row['book_id'], $row['title'], $row['author'], $row['price'], $row['genre'], $row['year'], $row['is_borrowed']);
        }
    }
}


if ($current_view === 'borrowed_books') {
    if (isset($_GET['selected_member_id']) && !empty($_GET['selected_member_id'])) {
        $selected_member_id = (int)$_GET['selected_member_id'];
        $member_stmt = $db->prepare("SELECT member_id, name, email, membership_date FROM Members WHERE member_id = ?");
        $member_stmt->bind_param("i", $selected_member_id);
        $member_stmt->execute();
        $member_result = $member_stmt->get_result();
        if ($member_data = $member_result->fetch_assoc()) {
            $current_member = new Member($member_data['member_id'], $member_data['name'], $member_data['email'], $member_data['membership_date']);
            $borrowed_by_member = $current_member->viewBorrowedBooks($db);
            $selected_member_name = $current_member->name;
        }
        $member_stmt->close();
    }
}

// Data for Dashboard View
$total_books = 0;
$total_members = 0;
$total_borrowed = 0;
if ($current_view === 'dashboard') {
    $total_books_res = $db->query("SELECT COUNT(*) as count FROM Books");
    $total_books = $total_books_res->fetch_assoc()['count'];
    $total_members_res = $db->query("SELECT COUNT(*) as count FROM Members");
    $total_members = $total_members_res->fetch_assoc()['count'];
    $total_borrowed_res = $db->query("SELECT COUNT(*) as count FROM BookLoans WHERE return_date IS NULL");
    $total_borrowed = $total_borrowed_res->fetch_assoc()['count'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Optional: Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-book-reader logo-icon"></i>
                <h1>LibrarySys</h1>
            </div>
                       <nav class="sidebar-nav">
                <a href="?view=dashboard" class="<?php echo ($current_view === 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
                <a href="?view=available_books" class="<?php echo ($current_view === 'available_books') ? 'active' : ''; ?>">
                    <i class="fas fa-book-open"></i> <span>Available Books</span>
                </a>
                <a href="?view=borrowed_books" class="<?php echo ($current_view === 'borrowed_books') ? 'active' : ''; ?>">
                    <i class="fas fa-exchange-alt"></i> <span>Borrowed Books</span>
                </a>
                <a href="?view=manage_members" class="<?php echo ($current_view === 'manage_members') ? 'active' : ''; ?>">
                    <i class="fas fa-users-cog"></i> <span>Manage Members</span>
                </a>
                <a href="?view=manage_books" class="<?php echo ($current_view === 'manage_books') ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i> <span>Manage Books</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <p>© <?php echo date("Y"); ?></p>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                 <div class="header-title-and-toggle"> <button class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button> <h2>
                        <?php
                        switch ($current_view) {
                            case 'dashboard': echo 'System Overview'; break;
                            case 'available_books': echo 'Available Library Books'; break;
                            case 'borrowed_books': echo 'Member Loan Records'; break;
                            case 'manage_members': echo 'Member Management'; break;
                            case 'manage_books': echo 'Book Inventory Management'; break;
                            default: echo 'Library Dashboard';
                        }
                        ?>
                    </h2>
                </div>
                <div class="user-profile">
                    <i class="fas fa-user-circle"></i>
                    <span>Admin</span>
                    <!-- Add logout or settings icon here if needed -->
                </div>
            </header>

            <div class="content-area">
                <?php if ($message): ?>
                    <div class="message <?php echo htmlspecialchars($message_type); ?>">
                        <i class="fas <?php echo ($message_type === 'success') ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php
                // --- Render content based on view ---
                if ($current_view === 'dashboard'):
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
                                <h4>Total Members</h4>
                                <p><?php echo $total_members; ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-hand-holding-heart stat-icon icon-borrowed"></i>
                            <div>
                                <h4>Books Borrowed</h4>
                                <p><?php echo $total_borrowed; ?></p>
                            </div>
                        </div>
                         <div class="stat-card">
                            <i class="fas fa-book-open stat-icon icon-available"></i>
                            <div>
                                <h4>Books Available</h4>
                                <p><?php echo $total_books - $total_borrowed; ?></p>
                            </div>
                        </div>
                    </section>
                    <section class="quick-actions section section-dashboard">
                        <h3>Quick Actions</h3>
                        <div class="action-buttons">
                            <a href="?view=manage_books#add-book-form" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add New Book</a>
                            <a href="?view=manage_members#add-member-form" class="btn btn-secondary"><i class="fas fa-user-plus"></i> Add New Member</a>
                        </div>
                    </section>

                <?php elseif ($current_view === 'available_books'): ?>
                    <section class="section section-available-books">
                        <!-- <h2>Available Books</h2> -->
                        <?php if (!empty($available_books)): ?>
                            <div class="book-grid">
                                <?php foreach ($available_books as $book): ?>
                                <div class="book-card available">
                                    <div class="book-card-image">
                                        <i class="fas <?php echo ($book instanceof Ebook) ? 'fa-tablet-alt' : 'fa-book'; ?>"></i>
                                    </div>
                                    <div class="book-card-content">
                                        <h3><?php echo htmlspecialchars($book->title); ?></h3>
                                        <p class="author">By: <?php echo htmlspecialchars($book->author); ?></p>
                                        <p class="price">
                                            Price: $<?php
                                            if ($book instanceof Ebook) {
                                                echo htmlspecialchars($book->getDiscountedPrice()) . " <span class='original-price'>($". htmlspecialchars($book->price) .")</span>";
                                            } else {
                                                echo htmlspecialchars($book->price);
                                            }
                                            ?>
                                        </p>
                                        <p class="genre">Genre: <?php echo htmlspecialchars($book->genre ?? 'N/A'); ?></p>
                                        <p class="year">Year: <?php echo htmlspecialchars($book->year ?? 'N/A'); ?></p>
                                        <form method="POST" action="library_test.php?view=available_books" class="borrow-form">
                                            <input type="hidden" name="action" value="borrow">
                                            <input type="hidden" name="book_id" value="<?php echo $book->book_id; ?>">
                                            <div class="form-group-inline">
                                                <select name="member_id" required title="Select member to borrow" class="form-control-sm">
                                                    <option value="">Borrow for...</option>
                                                    <?php foreach ($members_list as $member_opt): ?>
                                                        <option value="<?php echo $member_opt->member_id; ?>">
                                                            <?php echo htmlspecialchars($member_opt->name); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-hand-holding-heart"></i> Borrow
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="empty-state"><i class="fas fa-book-dead"></i> No books currently available or all books are borrowed.</p>
                        <?php endif; ?>
                    </section>

                <?php elseif ($current_view === 'borrowed_books'): ?>
                    <section class="section section-borrowed-books">
                        <!-- <h2>View Member's Borrowed Books</h2> -->
                        <form method="GET" action="library_test.php" class="filter-form">
                            <input type="hidden" name="view" value="borrowed_books">
                            <div class="form-group">
                                <label for="selected_member_id">Select Member:</label>
                                <select name="selected_member_id" id="selected_member_id" onchange="this.form.submit()" class="form-control">
                                    <option value="">-- Select Member --</option>
                                    <?php foreach ($members_list as $member_opt): ?>
                                        <option value="<?php echo $member_opt->member_id; ?>" <?php echo ($selected_member_id == $member_opt->member_id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($member_opt->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>

                        <?php if ($selected_member_id && !empty($borrowed_by_member)): ?>
                            <h3>Books Borrowed by <?php echo htmlspecialchars($selected_member_name); ?></h3>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-book"></i> Title</th>
                                        <th><i class="fas fa-user-edit"></i> Author</th>
                                        <th><i class="fas fa-calendar-alt"></i> Loan Date</th>
                                        <th><i class="fas fa-info-circle"></i> Type</th>
                                        <th><i class="fas fa-undo-alt"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($borrowed_by_member as $borrowed_item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($borrowed_item['title']); ?></td>
                                            <td><?php echo htmlspecialchars($borrowed_item['author']); ?></td>
                                            <td><?php echo htmlspecialchars(date("M d, Y", strtotime($borrowed_item['loan_date']))); ?></td>
                                            <td><?php echo ($borrowed_item['is_ebook']) ? 'E-Book <i class="fas fa-tablet-alt text-muted"></i>' : 'Physical <i class="fas fa-book-open text-muted"></i>'; ?></td>
                                            <td>
                                                <form method="POST" action="library_test.php?view=borrowed_books" style="display:inline;">
                                                    <input type="hidden" name="action" value="return">
                                                    <input type="hidden" name="book_id" value="<?php echo $borrowed_item['book_id']; ?>">
                                                    <input type="hidden" name="selected_member_id_return" value="<?php echo $selected_member_id; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-undo"></i> Return
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php elseif ($selected_member_id): ?>
                            <p class="empty-state"><i class="fas fa-folder-open"></i> <?php echo htmlspecialchars($selected_member_name); ?> has no books currently borrowed.</p>
                        <?php else: ?>
                            <p class="empty-state"><i class="fas fa-mouse-pointer"></i> Select a member to see their borrowed books.</p>
                        <?php endif; ?>
                    </section>

                <?php elseif ($current_view === 'manage_members'): ?>
                     <div class="management-layout">
                        <section class="section section-manage-members form-section" id="add-member-form">
                            <h3><i class="fas fa-user-plus"></i> Add New Member</h3>
                            <form method="POST" action="library_test.php?view=manage_members" class="styled-form">
                                <input type="hidden" name="action" value="add_member">
                                <div class="form-group">
                                    <label for="member_name">Name:</label>
                                    <input type="text" id="member_name" name="member_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="member_email">Email:</label>
                                    <input type="email" id="member_email" name="member_email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Member</button>
                                </div>
                            </form>
                        </section>
                        <section class="section section-manage-members list-section">
                             <h3><i class="fas fa-users"></i> Current Members</h3>
                             <?php if (!empty($members_list)): ?>
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-id-badge"></i> ID</th>
                                            <th><i class="fas fa-user"></i> Name</th>
                                            <th><i class="fas fa-envelope"></i> Email</th>
                                            <th><i class="fas fa-calendar-check"></i> Member Since</th>
                                            <!-- Add actions like edit/delete if needed -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($members_list as $member): ?>
                                        <tr>
                                            <td><?php echo $member->member_id; ?></td>
                                            <td><?php echo htmlspecialchars($member->name); ?></td>
                                            <td><?php echo htmlspecialchars($member->email); ?></td>
                                            <td><?php echo htmlspecialchars(date("M d, Y", strtotime($member->membership_date))); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                             <?php else: ?>
                                <p class="empty-state"><i class="fas fa-user-slash"></i> No members found.</p>
                             <?php endif; ?>
                        </section>
                    </div>


                <?php elseif ($current_view === 'manage_books'): ?>
                    <div class="management-layout">
                        <section class="section section-manage-books form-section" id="add-book-form">
                            <h3><i class="fas fa-book-medical"></i> Add New Book</h3>
                            <form method="POST" action="library_test.php?view=manage_books" class="styled-form">
                                <input type="hidden" name="action" value="add_book">
                                <div class="form-group">
                                    <label for="book_title">Title:</label>
                                    <input type="text" id="book_title" name="book_title" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="book_author">Author:</label>
                                    <input type="text" id="book_author" name="book_author" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="book_price">Price:</label>
                                    <input type="number" id="book_price" name="book_price" step="0.01" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="book_genre">Genre:</label>
                                    <input type="text" id="book_genre" name="book_genre" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="book_year">Year:</label>
                                    <input type="number" id="book_year" name="book_year" min="1000" max="<?php echo date('Y'); ?>" class="form-control">
                                </div>
                                <div class="form-group checkbox-group">
                                    <input type="checkbox" id="is_ebook" name="is_ebook" value="1">
                                    <label for="is_ebook">Is E-Book?</label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Book</button>
                                </div>
                            </form>
                        </section>
                        <section class="section section-manage-books list-section">
                            <h3><i class="fas fa-list-ul"></i> All Books Inventory</h3>
                             <?php if (!empty($all_books_for_management)): ?>
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Price</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <!-- Add actions like edit/delete if needed -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($all_books_for_management as $book_item): ?>
                                        <tr>
                                            <td><?php echo $book_item->book_id; ?></td>
                                            <td><?php echo htmlspecialchars($book_item->title); ?></td>
                                            <td><?php echo htmlspecialchars($book_item->author); ?></td>
                                            <td>$<?php echo htmlspecialchars($book_item->price); ?></td>
                                            <td><?php echo ($book_item instanceof Ebook) ? 'E-Book' : 'Physical'; ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $book_item->is_borrowed ? 'status-borrowed' : 'status-available'; ?>">
                                                    <?php echo $book_item->is_borrowed ? 'Borrowed' : 'Available'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                             <?php else: ?>
                                <p class="empty-state"><i class="fas fa-book-dead"></i> No books in inventory.</p>
                             <?php endif; ?>
                        </section>
                    </div>
                <?php endif; ?>

            </div> <!-- /content-area -->
        </main>
    </div> <!-- /dashboard-layout -->

 <script>
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleButton = document.querySelector('.sidebar-toggle-btn'); // Make sure this button exists

    if (toggleButton && sidebar && mainContent) {
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('expanded'); // For mobile full expansion
            // For desktop-like collapse/expand, you might need different classes or logic
            // This example focuses on a mobile scenario where 'expanded' shows the full sidebar
            // and without it, it's either narrow or hidden based on CSS media queries.
            
            // Adjust this logic based on how you want collapse to behave on desktop vs mobile
            if (window.innerWidth <= 768) { // Mobile: expanded means full width
                if (sidebar.classList.contains('expanded')) {
                    // Optional: overlay effect for main content when sidebar is open on mobile
                    // mainContent.classList.add('sidebar-open-overlay');
                } else {
                    // mainContent.classList.remove('sidebar-open-overlay');
                }
            } else { // Desktop: toggle a 'collapsed' class for the narrow view
                sidebar.classList.toggle('collapsed-desktop'); // You'd need CSS for .collapsed-desktop
                mainContent.classList.toggle('main-content-expanded-desktop');
            }
        });
    }

    // Handle initial state based on screen size for desktop collapse
    function checkDesktopCollapse() {
        if (window.innerWidth > 768 && window.innerWidth <= 1024) { // Example: auto-collapse on medium desktops
            // sidebar.classList.add('collapsed-desktop');
            // mainContent.classList.add('main-content-expanded-desktop');
        } else if (window.innerWidth > 1024) {
            // sidebar.classList.remove('collapsed-desktop');
            // mainContent.classList.remove('main-content-expanded-desktop');
        }
    }
    // window.addEventListener('resize', checkDesktopCollapse);
    // checkDesktopCollapse(); // Initial check
</script>
</body>
</html>
<?php
$db->close();
?>