<?php
require_once 'includes/auth_check.php'; // Session started here, user is authenticated
require_once 'includes/db_connect.php'; // db_connect also starts session if not already
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <?php include 'includes/header.php'; ?>

            <div class="content-area">
                <?php
                // Flash messages for actions
                if (isset($_SESSION['message'])) {
                    echo '<div class="message ' . htmlspecialchars($_SESSION['message_type']) . '"><i class="fas ' . ($_SESSION['message_type'] == 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle') . '"></i> ' . htmlspecialchars($_SESSION['message']) . '</div>';
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                }

                $view = isset($_GET['view']) ? $_GET['view'] : 'home';

                switch ($view) {
                    case 'available_books': // New
                        include 'views/available_books_content.php'; // New
                        break; // New
                    case 'my_borrowed_books': // New
                        include 'views/my_borrowed_books_content.php'; // New
                        break; // New
                    case 'manage_all_books': // This is for the full CRUD
                        include 'views/manage_all_books_content.php';
                        break;

                   
                    case 'add_book':
                        include 'views/add_book_form_content.php';
                        break;
                    case 'edit_book':
                        include 'views/edit_book_form_content.php';
                        break;
                    case 'view_users': // New Case
                        include 'views/view_users_content.php';
                    case 'profile':
                        include 'views/profile_content.php';
                        break;
                    case 'home':
                    default:
                        include 'views/dashboard_home_content.php';
                        break;
                    
                }
                ?>
            </div> <!-- /content-area -->
        </main>
    </div> <!-- /dashboard-layout -->
    <?php include 'includes/footer.php'; ?>