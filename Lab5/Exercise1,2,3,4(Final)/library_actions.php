<?php
require_once 'includes/auth_check.php';
require_once 'includes/db_connect.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$current_user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($action === 'borrow' && isset($_POST['book_id']) && isset($_POST['borrowing_user_id'])) {
        $book_id = (int)$_POST['book_id'];
        $member_id_to_borrow = (int)$_POST['borrowing_user_id']; // Could be current user or another if admin is borrowing for someone

        // 1. Check if book is already borrowed (and not returned)
        $stmt_check = $db->prepare("SELECT loan_id FROM BookLoans WHERE book_id = ? AND return_date IS NULL");
        $stmt_check->bind_param("i", $book_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $_SESSION['message'] = "This book is currently already borrowed.";
            $_SESSION['message_type'] = "error";
        } else {
            // 2. Insert into BookLoans
            $loan_date = date('Y-m-d');
            // Optional: Calculate due_date (e.g., 2 weeks from loan_date)
            // $due_date = date('Y-m-d', strtotime($loan_date . ' +14 days'));

            $stmt_borrow = $db->prepare("INSERT INTO BookLoans (book_id, member_id, loan_date) VALUES (?, ?, ?)");
            // Add due_date if using: $stmt_borrow = $db->prepare("INSERT INTO BookLoans (book_id, member_id, loan_date, due_date) VALUES (?, ?, ?, ?)");
            // $stmt_borrow->bind_param("iiss", $book_id, $member_id_to_borrow, $loan_date, $due_date);
            $stmt_borrow->bind_param("iis", $book_id, $member_id_to_borrow, $loan_date);

            if ($stmt_borrow->execute()) {
                $_SESSION['message'] = "Book borrowed successfully!";
                $_SESSION['message_type'] = "success";
                // Optional: Update an `is_borrowed` flag on the Books table if you use one for quick checks
                // $db->query("UPDATE Books SET is_borrowed = TRUE WHERE book_id = $book_id");
            } else {
                $_SESSION['message'] = "Error borrowing book: " . $db->error;
                $_SESSION['message_type'] = "error";
            }
            $stmt_borrow->close();
        }
        $stmt_check->close();
        header("Location: dashboard.php?view=available_books");
        exit;

    } elseif ($action === 'return' && isset($_POST['loan_id'])) {
        $loan_id = (int)$_POST['loan_id'];
        $book_id_to_return = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0; // For the Books.is_borrowed flag

        // 1. Update BookLoans table
        $return_date = date('Y-m-d');
        $stmt_return = $db->prepare("UPDATE BookLoans SET return_date = ? WHERE loan_id = ? AND member_id = ? AND return_date IS NULL");
        $stmt_return->bind_param("sii", $return_date, $loan_id, $current_user_id); // Ensure current user can only return their own books

        if ($stmt_return->execute()) {
            if ($stmt_return->affected_rows > 0) {
                $_SESSION['message'] = "Book returned successfully!";
                $_SESSION['message_type'] = "success";
                // Optional: Update an `is_borrowed` flag on the Books table
                // if ($book_id_to_return > 0) {
                //     $db->query("UPDATE Books SET is_borrowed = FALSE WHERE book_id = $book_id_to_return");
                // }
            } else {
                $_SESSION['message'] = "Could not return book. It might already be returned or not borrowed by you.";
                $_SESSION['message_type'] = "warning";
            }
        } else {
            $_SESSION['message'] = "Error returning book: " . $db->error;
            $_SESSION['message_type'] = "error";
        }
        $stmt_return->close();
        header("Location: dashboard.php?view=my_borrowed_books");
        exit;
    }
}

// Fallback
$_SESSION['message'] = "Invalid library action.";
$_SESSION['message_type'] = "error";
header("Location: dashboard.php?view=home");
exit;
?>