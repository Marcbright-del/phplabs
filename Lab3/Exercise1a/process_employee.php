<?php
session_start(); // Start session to store messages
require_once 'dbconfig.php'; // Include database configuration

$message = '';
$message_type = 'error'; // Default to error

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $emp_name = trim($_POST['emp_name']);
    $emp_salary = trim($_POST['emp_salary']);
    $emp_dept_id = trim($_POST['emp_dept_id']);

    // --- Server-side Validation ---
    $errors = [];
    if (empty($emp_name)) {
        $errors[] = "Employee name is required.";
    }
    if (empty($emp_salary)) {
        $errors[] = "Salary is required.";
    } elseif (!is_numeric($emp_salary) || $emp_salary <= 0) {
        $errors[] = "Salary must be a positive number.";
    }
    if (empty($emp_dept_id)) {
        $errors[] = "Department is required.";
    } elseif (!filter_var($emp_dept_id, FILTER_VALIDATE_INT)) {
        $errors[] = "Invalid department selected.";
    }

    if (empty($errors)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Employee (emp_name, emp_salary, emp_dept_id) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            // s = string, d = double (for decimal/numeric), i = integer
            mysqli_stmt_bind_param($stmt, "sdi", $param_name, $param_salary, $param_dept_id);

            // Set parameters
            $param_name = $emp_name;
            $param_salary = (float)$emp_salary; // Cast to float for safety
            $param_dept_id = (int)$emp_dept_id;   // Cast to int for safety

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Employee added successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error: Could not execute query: " . mysqli_stmt_error($stmt);
                $_SESSION['message_type'] = "error";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Error: Could not prepare query: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    } else {
        // Store errors in session to display on the form page
        $_SESSION['message'] = "Please correct the following errors: <ul><li>" . implode("</li><li>", $errors) . "</li></ul>";
        $_SESSION['message_type'] = "error";
        // Optionally, you can also store old input values in session to repopulate the form
        // $_SESSION['old_input'] = $_POST;
    }

    // Close connection
    mysqli_close($conn);

    // Redirect back to the form page (or to view page on success)
    header("location: add_employee.php");
    exit;

} else {
    // If not a POST request, redirect to form
    header("location: add_employee.php");
    exit;
}
?>