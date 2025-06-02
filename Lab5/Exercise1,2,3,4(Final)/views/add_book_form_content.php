<section class="section section-add-book">
    <h3><i class="fas fa-plus-circle"></i> Add New Book</h3>
    <form action="book_actions.php?action=add" method="post" class="styled-form">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" id="author" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" name="genre" id="genre" class="form-control">
        </div>
        <div class="form-group">
            <label for="year">Year</label>
            <input type="number" name="year" id="year" class="form-control" min="1000" max="<?php echo date('Y'); ?>">
        </div>
        <div class="form-group form-buttons">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Book</button>
            <a href="dashboard.php?view=manage_all_books" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</section>