
<?php
require_once 'vendor/autoload.php';
session_start();

// Load environment variables from .env file
require_once 'includes/dotenv_loader.php';

// google_login.php
$clientID = getenv('GOOGLE_CLIENT_ID');  // use environment variables
$clientSecret = getenv('GOOGLE_CLIENT_SECRET');

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri('http://localhost:8000/google_oauth_callback.php');
$client->addScope('email');
$client->addScope('profile');
$client->setAccessType('offline');
$client->setPrompt('consent');

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit;
