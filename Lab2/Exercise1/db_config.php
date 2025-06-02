<?php
define('DB_SERVER', 'localhost'); // Or your DB host (e.g., 127.0.0.1)
define('DB_USERNAME', 'root');    // Your MySQL username
define('DB_PASSWORD', 'Betozour30');        // Your MySQL password
define('DB_NAME', 'WebAppsDB');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // For a real application, log this error instead of die()
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Optional: Set charset (good practice)
$conn->set_charset("utf8mb4");
?>