<?php
session_start(); // Start session to handle messages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="user_form.php">Add User</a>
        <a href="view_users.php">View Users</a>
    </nav>

    <div class="container">
        <h1>Add New User</h1>

        <?php
        // Display error messages if any
        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
            echo '<div class="message error"><ul>';
            foreach ($_SESSION['errors'] as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul></div>';
            unset($_SESSION['errors']); // Clear errors after displaying
        }

        // Display success message if any
        if (isset($_SESSION['success_message'])) {
            echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']); // Clear message after displaying
        }

        // Preserve form data if validation failed
        $name = isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : '';
        $email = isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : '';
        $age = isset($_SESSION['form_data']['age']) ? htmlspecialchars($_SESSION['form_data']['age']) : '';
        unset($_SESSION['form_data']); // Clear form data after using
        ?>

        <form action="process_form.php" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $age; ?>" required min="1">
            </div>
            <div>
                <input type="submit" value="Add User">
            </div>
        </form>
    </div>
</body>
</html>