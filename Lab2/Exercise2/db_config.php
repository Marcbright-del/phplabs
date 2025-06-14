<?php
// db_config.php

define('DB_SERVER', 'localhost'); // or your db server
define('DB_USERNAME', 'root');    // your db username
define('DB_PASSWORD', 'Betozour30');        // your db password
define('DB_NAME', 'LibrarySystemDB2');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Start session for flash messages (used for interactivity/feedback)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>