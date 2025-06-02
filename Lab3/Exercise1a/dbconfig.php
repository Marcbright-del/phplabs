<?php
// dbconfig.php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Your MySQL username
define('DB_PASSWORD', 'Betozour30');     // Your MySQL password
define('DB_NAME', 'EmployeeDB2');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Set character set to utf8 (good practice)
mysqli_set_charset($conn, "utf8");
?>