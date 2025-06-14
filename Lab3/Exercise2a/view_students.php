<?php
session_start();
require_once 'dbconfig.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Student Records</h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message " . $_SESSION['message_type'] . "'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <a href="add_student.php" class="btn btn-add">Add New Student</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT student_id, name, email, phone_number FROM Students ORDER BY student_id ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["student_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["phone_number"]) . "</td>";
                        echo "<td class='actions'>";
                        echo "<a href='edit_student.php?id=" . htmlspecialchars($row["student_id"]) . "' class='btn btn-edit'>Edit</a> ";
                        echo "<a href='delete_student.php?id=" . htmlspecialchars($row["student_id"]) . "' class='btn btn-delete' onclick='return confirmDelete();'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No students found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this student record?");
        }
    </script>
</body>
</html>