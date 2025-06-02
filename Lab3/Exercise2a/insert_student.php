<?php
session_start();
require_once 'dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    // Optional: Validate phone number format more strictly if needed
    // if (!empty($phone_number) && !preg_match("/^[0-9\s\-\+\(\)]*$/", $phone_number)) {
    //    $errors[] = "Invalid phone number format.";
    // }


    if (empty($errors)) {
        // Check if email already exists
        $sql_check_email = "SELECT student_id FROM Students WHERE email = ?";
        if($stmt_check = $conn->prepare($sql_check_email)){
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();
            if($stmt_check->num_rows > 0){
                $errors[] = "This email address is already registered.";
            }
            $stmt_check->close();
        } else {
            $errors[] = "Error preparing email check: " . $conn->error;
        }
    }


    if (empty($errors)) {
        $sql = "INSERT INTO Students (name, email, phone_number) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            // sss denotes three string parameters
            $stmt->bind_param("sss", $param_name, $param_email, $param_phone);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_phone = $phone_number;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $_SESSION['message'] = "New student added successfully!";
                $_SESSION['message_type'] = "success";
                header("location: view_students.php");
                exit();
            } else {
                $_SESSION['message'] = "Error: Could not execute query: " . $stmt->error;
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error: Could not prepare query: " . $conn->error;
            $_SESSION['message_type'] = "error";
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['message'] = implode("<br>", $errors);
        $_SESSION['message_type'] = "error";
        // To retain form values on error, you could store them in session and repopulate,
        // or redirect back with query parameters. For simplicity, just redirecting to add page.
        header("location: add_student.php"); 
        exit();
    }

    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['message_type'] = "error";
    header("location: add_student.php");
    exit();
}
?>