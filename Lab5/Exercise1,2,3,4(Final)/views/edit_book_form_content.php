<?php
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$form_values = []; // Initialize an empty array for form values

// Check if there's form data stored in session from a previous failed attempt for THIS book
if (isset($_SESSION['form_data_edit']) && isset($_SESSION['form_data_edit_id']) && $_SESSION['form_data_edit_id'] == $book_id) {
    $form_values = $_SESSION['form_data_edit'];
    unset($_SESSION['form_data_edit']); // Clear it after use
    unset($_SESSION['form_data_edit_id']);
}


if ($book_id <= 0 && empty($form_values)) { // If no valid book_id and no form data
    $_SESSION['message'] = "Invalid book ID specified.";
    $_SESSION['message_type'] = "error";
    header("Location: dashboard.php?view=manage_all_books");
    exit;
}

$book_data_from_db = null;
if ($book_id > 0) { // Only fetch from DB if we have a valid book_id
    $stmt = $db->prepare("SELECT title, author, price, genre, year FROM Books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $book_data_from_db = $result->fetch_assoc();
    } else {
        // If no form_values from session and book not found in DB
        if (empty($form_values)) {
            $_SESSION['message'] = "Book not found.";
            $_SESSION['message_type'] = "error";
            header("Location: dashboard.php?view=manage_all_books");
            exit;
        }
    }
    $stmt->close();
}

// Prioritize session form data over DB data for repopulation
$title_val = isset($form_values['title']) ? $form_values['title'] : ($book_data_from_db['title'] ?? '');
$author_val = isset($form_values['author']) ? $form_values['author'] : ($book_data_from_db['author'] ?? '');
$price_val = isset($form_values['price']) ? $form_values['price'] : ($book_data_from_db['price'] ?? '');
$genre_val = isset($form_values['genre']) ? $form_values['genre'] : ($book_data_from_db['genre'] ?? '');
$year_val = isset($form_values['year']) ? $form_values['year'] : ($book_data_from_db['year'] ?? '');

?>
<section class="section section-edit-book">
    <h3><i class="fas fa-edit"></i> Edit Book: <?php echo htmlspecialchars($title_val ?: 'Details'); ?></h3>
    <form action="book_actions.php?action=edit&id=<?php echo $book_id; ?>" method="post" class="styled-form">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title_val); ?>" required>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" id="author" class="form-control" value="<?php echo htmlspecialchars($author_val); ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($price_val); ?>" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" name="genre" id="genre" class="form-control" value="<?php echo htmlspecialchars($genre_val); ?>">
        </div>
        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" name="year" id="year" class="form-control" value="<?php echo htmlspecialchars($year_val); ?>" min="1000" max="<?php echo date('Y'); ?>">
        </div>
        <div class="form-group form-buttons">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Book</button>
            <a href="dashboard.php?view=manage_all_books" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</section>