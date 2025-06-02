<?php
session_start();
require_once 'dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);

    $errors = [];

    if (empty($student_id) || !is_numeric($student_id)) {
        $errors[] = "Invalid student ID.";
    }
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    // Optional: Validate phone number format

    if (empty($errors)) {
        // Check if email already exists for a *different* student
        $sql_check_email = "SELECT student_id FROM Students WHERE email = ? AND student_id != ?";
        if($stmt_check = $conn->prepare($sql_check_email)){
            $stmt_check->bind_param("si", $email, $student_id);
            $stmt_check->execute();
            $stmt_check->store_result();
            if($stmt_check->num_rows > 0){
                $errors[] = "This email address is already registered by another student.";
            }
            $stmt_check->close();
        } else {
            $errors[] = "Error preparing email check: " . $conn->error;
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE Students SET name = ?, email = ?, phone_number = ? WHERE student_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $param_name, $param_email, $param_phone, $param_id);

            $param_name = $name;
            $param_email = $email;
            $param_phone = $phone_number;
            $param_id = $student_id;

            if ($stmt->execute()) {
                $_SESSION['message'] = "Student record updated successfully!";
                $_SESSION['message_type'] = "success";
                header("location: view_students.php");
                exit();
            } else {
                $_SESSION['message_edit'] = "Error: Could not execute update: " . $stmt->error; // Use _edit for edit page
                $_SESSION['message_edit_type'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['message_edit'] = "Error: Could not prepare update: " . $conn->error;
            $_SESSION['message_edit_type'] = "error";
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['message_edit'] = implode("<br>", $errors);
        $_SESSION['message_edit_type'] = "error";
    }
    
    // Redirect back to edit page if there were errors or if execution failed before redirect
    header("location: edit_student.php?id=" . $student_id);
    exit();

    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['message_type'] = "error";
    header("location: view_students.php");
    exit();
}
?>