<?php
define('DB_SERVER', 'localhost'); // Your database server (usually localhost)
define('DB_USERNAME', 'root');    // Your database username
define('DB_PASSWORD', 'Betozour30');        // Your database password
define('DB_NAME', 'StudentDB2');  // Your database name

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Optional: Set charset to utf8mb4 for better character support
$conn->set_charset("utf8mb4");
?>