<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Your DB username
define('DB_PASSWORD', 'Betozour30');   // Your DB password
define('DB_NAME', 'LibraryDB3');

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    error_log("Database Connection Error: " . $db->connect_error); // Log error
    die("Sorry, we're having some technical difficulties. Please try again later."); // User-friendly message
}
$db->set_charset("utf8mb4");

// Start session if not already started - crucial for auth
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>