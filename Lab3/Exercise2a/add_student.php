<?php
session_start(); // Start session to display messages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Student</h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message " . $_SESSION['message_type'] . "'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <form action="insert_student.php" method="POST" onsubmit="return validateForm()">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" pattern="[0-9\s\-\+\(\)]*" title="Enter a valid phone number">
                <!-- Basic pattern, can be improved -->
            </div>
            <button type="submit" class="btn">Add Student</button>
            <a href="view_students.php" class="btn" style="background-color: #7f8c8d; margin-left: 10px;">View Students</a>
        </form>
    </div>

    <script>
        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone_number').value.trim();

            if (name === "" || email === "") {
                alert("Name and Email are required.");
                return false;
            }

            // Basic email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            
            // Optional: Basic phone validation (allows digits, spaces, hyphens, plus, parentheses)
            // const phonePattern = /^[0-9\s\-\+\(\)]*$/;
            // if (phone !== "" && !phonePattern.test(phone)) {
            //     alert("Phone number contains invalid characters.");
            //     return false;
            // }
            return true;
        }
    </script>
</body>
</html>