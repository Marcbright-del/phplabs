<?php
require_once 'vendor/autoload.php'; // If using Composer
require_once 'includes/db_connect.php'; // For session start
require_once 'includes/dotenv_loader.php'; // Load environment variables

// google_login.php
$clientID = getenv('GOOGLE_CLIENT_ID');  // use environment variables
$clientSecret = getenv('GOOGLE_CLIENT_SECRET');

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri('http://localhost:8000/google_oauth_callback.php');
$client->addScope("email");
$client->addScope("profile");

// If already logged in via Google (or standard), redirect
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit;
?>
