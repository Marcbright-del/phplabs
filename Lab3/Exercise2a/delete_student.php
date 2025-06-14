<?php
session_start();
require_once 'dbconfig.php';

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $student_id = trim($_GET['id']);

    // Prepare a delete statement
    $sql = "DELETE FROM Students WHERE student_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = $student_id;

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['message'] = "Student record deleted successfully.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "No student found with that ID, or already deleted.";
                $_SESSION['message_type'] = "error"; // Or "warning"
            }
        } else {
            $_SESSION['message'] = "Error deleting record: " . $stmt->error;
            $_SESSION['message_type'] = "error";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Error preparing delete statement: " . $conn->error;
        $_SESSION['message_type'] = "error";
    }
    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request: No student ID provided for deletion.";
    $_SESSION['message_type'] = "error";
}

header("location: view_students.php");
exit();
?>