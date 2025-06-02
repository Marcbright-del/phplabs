<?php
session_start(); // Start session to store messages and form data
require_once 'db_config.php'; // Include database connection

$errors = [];
$name = '';
$email = '';
$age = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $age = trim($_POST['age']);

    // Store form data in session to repopulate form if validation fails
    $_SESSION['form_data'] = $_POST;

    // Validate Name
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (strlen($name) > 255) {
        $errors[] = "Name cannot be longer than 255 characters.";
    }

    // Validate Email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($email) > 255) {
        $errors[] = "Email cannot be longer than 255 characters.";
    } else {
        // Check if email already exists
        $stmt_check_email = $conn->prepare("SELECT id FROM Users WHERE email = ?");
        if ($stmt_check_email) {
            $stmt_check_email->bind_param("s", $email);
            $stmt_check_email->execute();
            $stmt_check_email->store_result();
            if ($stmt_check_email->num_rows > 0) {
                $errors[] = "Email already exists in the database.";
            }
            $stmt_check_email->close();
        } else {
            $errors[] = "Database error checking email: " . $conn->error;
        }
    }


    // Validate Age
    if (empty($age)) {
        $errors[] = "Age is required.";
    } elseif (!is_numeric($age)) {
        $errors[] = "Age must be a number.";
    } elseif ($age <= 0 || $age > 150) { // Assuming a reasonable age range
        $errors[] = "Age must be a positive number and realistic (e.g., 1-150).";
    } else {
        $age = (int)$age; // Cast to integer
    }

    // If no errors, proceed to insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO Users (name, email, age) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            // sss for string, string, string. If age is int, use "ssi"
            $stmt->bind_param("ssi", $param_name, $param_email, $param_age);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_age = $age;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "User added successfully!";
                unset($_SESSION['form_data']); // Clear form data on success
                header("Location: view_users.php"); // Redirect to view users page
                exit();
            } else {
                $_SESSION['errors'] = ["Oops! Something went wrong. Please try again later. Error: " . $stmt->error];
            }
            $stmt->close();
        } else {
             $_SESSION['errors'] = ["Database prepare error: " . $conn->error];
        }
    }

    // If there are errors, store them in session and redirect back to form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: user_form.php");
        exit();
    }

    $conn->close();

} else {
    // Not a POST request, redirect to form
    header("Location: user_form.php");
    exit();
}
?>