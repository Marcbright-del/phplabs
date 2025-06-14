<?php
session_start(); // Start session to display success messages
require_once 'db_config.php'; // Include database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="user_form.php">Add User</a>
        <a href="view_users.php">View Users</a>
    </nav>

    <div class="container">
        <h1>Registered Users</h1>

        <?php
        // Display success message if any (e.g., after adding a user)
        if (isset($_SESSION['success_message'])) {
            echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']); // Clear message after displaying
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, name, email, age FROM Users ORDER BY id ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["age"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>