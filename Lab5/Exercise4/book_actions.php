<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/auth_check.php'; // Ensures user is logged in
require_once 'includes/db_connect.php'; // Establishes DB connection and starts session

$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = $_SESSION['user_id']; // For tracking who added/updated


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- ADD BOOK ---
    if ($action == 'add') {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $price = (float)$_POST['price'];
        $genre = trim($_POST['genre']);
        $year = !empty($_POST['year']) ? (int)$_POST['year'] : null;

        if (empty($title) || empty($author) || $price <= 0) {
            $_SESSION['message'] = "Title, Author, and a valid Price are required.";
            $_SESSION['message_type'] = "error";
        } else {
            $stmt = $db->prepare("INSERT INTO Books (title, author, price, genre, year, added_by_user_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssi", $title, $author, $price, $genre, $year, $user_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Book " . htmlspecialchars($title) . " added successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error adding book: " . $db->error;
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
        }
        header("Location: dashboard.php?view=manage_all_books");
        exit;
    }

        // --- EDIT BOOK ---
    elseif ($action == 'edit' && isset($_GET['id'])) {
        $book_id = (int)$_GET['id'];
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $price = isset($_POST['price']) ? (float)$_POST['price'] : 0; // Ensure price is handled if not set
        $genre = trim($_POST['genre']);
        $year = !empty($_POST['year']) ? (int)$_POST['year'] : null;

        // --- Enhanced Validation ---
        $errors = [];
        if (empty($title)) {
            $errors[] = "Title is required.";
        }
        if (empty($author)) {
            $errors[] = "Author is required.";
        }
        if ($price <= 0) {
            $errors[] = "Price must be a positive value.";
        }
        // Add more specific validations if needed, e.g., max length for title/author

        if (!empty($errors)) {
            $_SESSION['message'] = implode("<br>", $errors); // Combine all error messages
            $_SESSION['message_type'] = "error";
            // To retain form values on redirect, you might store them in session,
            // then retrieve and populate them on the edit_book_form_content.php page.
            // For simplicity now, we just redirect.
            $_SESSION['form_data_edit'] = $_POST; // Store submitted data
            $_SESSION['form_data_edit_id'] = $book_id; // Store book id for context
            header("Location: dashboard.php?view=edit_book&id=" . $book_id);
            exit;
        } else {
            // Validation passed, proceed with database update
            // Clear any previous form data session if validation passes
            unset($_SESSION['form_data_edit']);
            unset($_SESSION['form_data_edit_id']);

            $stmt = $db->prepare("UPDATE Books SET title = ?, author = ?, price = ?, genre = ?, year = ? WHERE book_id = ?");
            $stmt->bind_param("ssdssi", $title, $author, $price, $genre, $year, $book_id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Book " . htmlspecialchars($title) . " updated successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error updating book: " . $db->error;
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
            // Redirect to the list view AFTER database operation and setting messages
            header("Location: dashboard.php?view=manage_all_books");
            exit;
        }
        // This part below is now unreachable due to exit statements in both branches of the if(!empty($errors))
        // header("Location: dashboard.php?view=manage_all_books"); // Original redirect before refinement
        // exit;
    }
// --- DELETE BOOK (Handles POST request from the form) ---
// Note: For critical delete operations, using a POST request with a CSRF token is more secure.
$action = $_POST['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete' && isset($_POST['id'])) {
    $book_id_to_delete = (int)$_POST['id'];

    // Validate the Book ID
    if ($book_id_to_delete <= 0) {
        $_SESSION['message'] = "Invalid Book ID specified for deletion.";
        $_SESSION['message_type'] = "error";
        header("Location: dashboard.php?view=manage_all_books");
        exit;
    }

    // Check if the database connection object $db is valid
    // This check is more of a safeguard; db_connect.php should handle connection errors.
    if (!$db || !($db instanceof mysqli) || $db->connect_error) {
        $_SESSION['message'] = "Database connection error. Cannot proceed with deletion.";
        $_SESSION['message_type'] = "error";
        error_log("book_actions.php (delete): Database connection is not valid or failed. DB error: " . ($db->connect_error ?? 'Unknown DB connection issue'));
        header("Location: dashboard.php?view=manage_all_books");
        exit;
    }

    // Fetch the book title before deleting (for a nicer success message and to confirm existence)
    $book_title_for_message = "Book with ID " . $book_id_to_delete; // Default message component
    $stmt_check = $db->prepare("SELECT title FROM Books WHERE book_id = ?");

    if ($stmt_check) {
        $stmt_check->bind_param("i", $book_id_to_delete);
        if ($stmt_check->execute()) {
            $result_check = $stmt_check->get_result();
            if ($book_data = $result_check->fetch_assoc()) {
                $book_title_for_message = $book_data['title'];
            } else {
                // Book not found, it might have been deleted already
                $_SESSION['message'] = "Book with ID " . $book_id_to_delete . " was not found.";
                $_SESSION['message_type'] = "warning"; // Use warning as it's not strictly an error in this script's flow
                $stmt_check->close();
                header("Location: dashboard.php?view=manage_all_books");
                exit;
            }
        } else {
            // Error executing the check statement
            $_SESSION['message'] = "Database error while checking book existence: " . $stmt_check->error;
            $_SESSION['message_type'] = "error";
            $stmt_check->close();
            header("Location: dashboard.php?view=manage_all_books");
            exit;
        }
        $stmt_check->close();
    } else {
        // Error preparing the check statement
        $_SESSION['message'] = "Database error (prepare check): " . $db->error;
        $_SESSION['message_type'] = "error";
        header("Location: dashboard.php?view=manage_all_books");
        exit;
    }

    // At this point, we know the book exists (or existed very recently) and we have its title.
    // Now, prepare and execute the DELETE statement.

    $stmt_delete = $db->prepare("DELETE FROM Books WHERE book_id = ?");
    if ($stmt_delete) {
        $stmt_delete->bind_param("i", $book_id_to_delete);

        if ($stmt_delete->execute()) {
            // Check if any row was actually affected (deleted)
            if ($stmt_delete->affected_rows > 0) {
                $_SESSION['message'] = "Book " . htmlspecialchars($book_title_for_message) . " has been permanently deleted.";
                $_SESSION['message_type'] = "success";
                // If you have a BookLoans table with a FOREIGN KEY to Books.book_id
                // and it's set to ON DELETE CASCADE, related loans will be auto-deleted.
                // Otherwise, you'd need to delete from BookLoans here first, possibly in a transaction.
            } else {
                // No rows affected - this means the book_id didn't match any rows during the delete.
                // This could happen if it was deleted by another process between the check and the delete.
                $_SESSION['message'] = "Book '<strong>" . htmlspecialchars($book_title_for_message) . "</strong>' could not be deleted (it may have already been removed).";
                $_SESSION['message_type'] = "warning";
            }
        } else {
            // Error executing the delete statement
            $_SESSION['message'] = "Error deleting book '<strong>" . htmlspecialchars($book_title_for_message) . "</strong>': " . $stmt_delete->error;
            $_SESSION['message_type'] = "error";
            // Consider logging $stmt_delete->errno as well for specific DB errors.
        }
        $stmt_delete->close();
    } else {
        // Error preparing the delete statement
        $_SESSION['message'] = "Database error (prepare delete): " . $db->error;
        $_SESSION['message_type'] = "error";
    }

    // Always redirect back to the manage books page after attempting delete
    header("Location: dashboard.php?view=manage_all_books");
    exit;

} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- ADD BOOK ACTION ---
    if ($action == 'add') {
        // ... (Your existing validated ADD logic here) ...
        // Ensure it ends with:
        // header("Location: dashboard.php?view=manage_all_books");
        // exit;
    }
    // --- EDIT BOOK ACTION ---
    elseif ($action == 'edit' && isset($_GET['id'])) { // Or use $_POST['book_id'] if you pass it as hidden field
        // ... (Your existing validated EDIT logic here) ...
        // Ensure it ends with:
        // header("Location: dashboard.php?view=manage_all_books");
        // exit;
    }
    // --- (Other POST actions like profile picture upload if in this file) ---
    // elseif ($action === 'upload_profile_pic') { ... }
    // elseif ($action === 'remove_profile_pic') { ... }
    else {
        $_SESSION['message'] = "Invalid POST action specified.";
        $_SESSION['message_type'] = "error";
        header("Location: dashboard.php?view=manage_all_books"); // Or a more general error page/dashboard home
        exit;
    }
} else {
    // If it's a GET request but not for 'delete', or if action is missing for GET
    // Or if method is not POST and not a GET delete.
    $_SESSION['message'] = "Invalid request method or action not specified correctly.";
    $_SESSION['message_type'] = "error";
    header("Location: dashboard.php?view=home"); // Redirect to a safe page
    exit;
}
}