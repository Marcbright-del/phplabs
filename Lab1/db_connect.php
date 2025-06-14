<?php
// Database connection parameters
$servername = "localhost"; // e.g., localhost or 127.0.0.1
$username = "root"; // Your MySQL username
$password = "Betozour30"; // Your MySQL password
$dbname = "LibraryDB"; 

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // If connection fails, stop script execution and display error
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to utf8mb4 for broader character support
$conn->set_charset("utf8mb4");

// This file ($conn) is now available to any script that includes it.
?>
