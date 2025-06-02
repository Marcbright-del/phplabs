<?php
define('DB_SERVER', 'localhost'); // or your db host
define('DB_USERNAME', 'root');    // your db username
define('DB_PASSWORD', 'Betozour30');        // your db password
define('DB_NAME', 'LibraryDB2');

// Attempt to connect to MySQL database
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($db->connect_error) {
    die("ERROR: Could not connect. " . $db->connect_error);
}

// Set charset to utf8mb4 for better character support
$db->set_charset("utf8mb4");
?>