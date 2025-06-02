<?php
require_once 'db_config.php'; // Includes session_start()

$errors = [];
$book_title = '';
$author_id = '';
$genre = '';
$price = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve input data
    $book_title = trim($_POST['book_title']);
    $author_id = trim($_POST['author_id']);
    $genre = trim($_POST['genre']);
    $price_input = trim($_POST['price']);

    // Validate Book Title
    if (empty($book_title)) {
        $errors[] = "Book title is required.";
    } elseif (strlen($book_title) > 255) {
        $errors[] = "Book title cannot exceed 255 characters.";
    }

    // Validate Author ID
    if (empty($author_id)) {
        $errors[] = "Author is required.";
    } elseif (!filter_var($author_id, FILTER_VALIDATE_INT)) {
        $errors[] = "Invalid author selected.";
    } else {
        // Optional: Check if author_id actually exists in Authors table
        $check_author_stmt = mysqli_prepare($conn, "SELECT author_id FROM Authors WHERE author_id = ?");
        mysqli_stmt_bind_param($check_author_stmt, "i", $author_id);
        mysqli_stmt_execute($check_author_stmt);
        mysqli_stmt_store_result($check_author_stmt);
        if (mysqli_stmt_num_rows($check_author_stmt) == 0) {
            $errors[] = "Selected author does not exist.";
        }
        mysqli_stmt_close($check_author_stmt);
    }

    // Validate Genre (optional field, but if provided, check length)
    if (!empty($genre) && strlen($genre) > 100) {
        $errors[] = "Genre cannot exceed 100 characters.";
    }
    // If genre is empty, we can store it as NULL or an empty string.
    // For this example, we'll allow empty string to be inserted.
    // If you want to store NULL: $genre = empty($genre) ? null : $genre;

    // Validate Price
    if (empty($price_input) && $price_input !== '0') { // Allow 0 as a valid price
        $errors[] = "Price is required.";
    } elseif (!is_numeric($price_input)) {
        $errors[] = "Price must be a valid number.";
    } elseif (floatval($price_input) < 0) {
        $errors[] = "Price cannot be negative.";
    } else {
        $price = floatval($price_input); // Convert to float for database
    }


    if (empty($errors)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Books (book_title, author_id, genre, price) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            // s = string, i = integer, d = double (for decimal/float)
            mysqli_stmt_bind_param($stmt, "sisd", $param_title, $param_author_id, $param_genre, $param_price);

            // Set parameters
            $param_title = $book_title;
            $param_author_id = $author_id;
            $param_genre = $genre; // Use $genre as is, it could be an empty string
            $param_price = $price;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Book added successfully!";
                $_SESSION['message_type'] = "success";
                header("Location: add_book.php"); // Redirect to form page
                exit();
            } else {
                $_SESSION['message'] = "ERROR: Could not execute query: " . mysqli_error($conn);
                $_SESSION['message_type'] = "error";
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "ERROR: Could not prepare query: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    } else {
        // Store errors in session to display on the form page
        $error_message = "<strong>Please correct the following errors:</strong><ul>";
        foreach ($errors as $error) {
            $error_message .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $error_message .= "</ul>";
        $_SESSION['message'] = $error_message;
        $_SESSION['message_type'] = "error";

        // Optional: Store input values in session to repopulate form (more advanced)
        $_SESSION['form_data'] = $_POST;
    }

    mysqli_close($conn);
    header("Location: add_book.php"); // Redirect back to form page to show messages
    exit();

} else {
    // If not a POST request, redirect to the form
    header("Location: add_book.php");
    exit();
}
?>