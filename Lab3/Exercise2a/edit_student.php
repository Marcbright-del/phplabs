<?php
session_start();
require_once 'dbconfig.php';

$student_id = null;
$name = $email = $phone_number = "";

// Check if ID is provided
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $student_id = trim($_GET['id']);

    // Prepare a select statement
    $sql = "SELECT name, email, phone_number FROM Students WHERE student_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = $student_id;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $name = $row['name'];
                $email = $row['email'];
                $phone_number = $row['phone_number'];
            } else {
                $_SESSION['message'] = "Error: Student not found.";
                $_SESSION['message_type'] = "error";
                header("location: view_students.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
            $_SESSION['message_type'] = "error";
            header("location: view_students.php");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        $_SESSION['message_type'] = "error";
        header("location: view_students.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Error: No student ID provided for editing.";
    $_SESSION['message_type'] = "error";
    header("location: view_students.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Student Information</h1>

        <?php
        if (isset($_SESSION['message_edit'])) { // Use a different session variable for edit page messages
            echo "<p class='message " . $_SESSION['message_edit_type'] . "'>" . $_SESSION['message_edit'] . "</p>";
            unset($_SESSION['message_edit']);
            unset($_SESSION['message_edit_type']);
        }
        ?>
        
        <form action="update_student.php" method="POST" onsubmit="return validateEditForm()">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div>
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" pattern="[0-9\s\-\+\(\)]*">
            </div>
            <button type="submit" class="btn">Update Student</button>
            <a href="view_students.php" class="btn" style="background-color: #7f8c8d; margin-left: 10px;">Cancel</a>
        </form>
    </div>
    <script>
        function validateEditForm() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (name === "" || email === "") {
                alert("Name and Email are required.");
                return false;
            }
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>