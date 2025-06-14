<?php
ob_start(); // Start output buffering
require_once 'vendor/autoload.php';
require_once 'includes/db_connect.php'; // For DB and session
require_once 'includes/dotenv_loader.php'; // Load environment variables

// Define the path to the certificate bundle
$certPath = __DIR__ . '/certs/cacert.pem';

// google_login.php
$clientID = getenv('GOOGLE_CLIENT_ID');  // use environment variables
$clientSecret = getenv('GOOGLE_CLIENT_SECRET');

// Create the client with SSL verification
$client = new Google_Client();


$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri('http://localhost:8000/google_oauth_callback.php');

// Add these lines
$client->addScope('email');
$client->addScope('profile');
$client->setAccessType('offline');
$client->setPrompt('consent');

// Error handling function
function redirectWithError($message, $page = 'login.php') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = "error";
    header('Location: ' . $page);
    exit();
}

try {
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (!isset($token['error'])) {
            $client->setAccessToken($token['access_token']);

            // Get profile info
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            
            $google_id = $google_account_info->id;
            $email = $google_account_info->email;
            $name = $google_account_info->name; // Or givenName / familyName

            // Check if user exists in your DB
            $stmt = $db->prepare("SELECT id, username FROM users WHERE google_id = ? OR email = ?");
            $stmt->bind_param("ss", $google_id, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                // User exists, log them in
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["username"] = $user['username']; // Use local username
                // Optionally update google_id if they logged in via email first and now Google
                if(empty($user['google_id'])) {
                    $update_stmt = $db->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $google_id, $user['id']);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            } else {
                // New user, register them
                // Create a unique username if needed, e.g., from email prefix or full name
                $username_parts = explode('@', $email);
                $base_username = preg_replace('/[^a-zA-Z0-9_]/', '', $username_parts[0]);
                $username_candidate = $base_username;
                $counter = 1;
                // Ensure username is unique
                while(true){
                    $check_user_stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
                    $check_user_stmt->bind_param("s", $username_candidate);
                    $check_user_stmt->execute();
                    if($check_user_stmt->get_result()->num_rows == 0){
                        break;
                    }
                    $username_candidate = $base_username . $counter++;
                    $check_user_stmt->close();
                }

                $insert_stmt = $db->prepare("INSERT INTO users (username, email, google_id, password) VALUES (?, ?, ?, ?)");
                // Generate a random, unusable password for Google-only accounts or leave NULL if schema allows
                $random_password_placeholder = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
                $insert_stmt->bind_param("ssss", $username_candidate, $email, $google_id, $random_password_placeholder);
                if ($insert_stmt->execute()) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_id"] = $db->insert_id;
                    $_SESSION["username"] = $username_candidate;
                } else {
                    redirectWithError("Error registering with Google: " . $db->error, 'register.php');
                }
                $insert_stmt->close();
            }
            $stmt->close();
            $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'dashboard.php';
            unset($_SESSION['redirect_url']);
            header('Location: ' . $redirect_url);
            ob_end_flush(); // Flush the output buffer
            exit();
        } else {
            redirectWithError("Google Authentication Error: " . htmlspecialchars($token['error_description'] ?? 'Unknown error'));
        }
    } else {
        redirectWithError("No authentication code received");
    }
} catch (Exception $e) {
    redirectWithError("Error: " . $e->getMessage());
}
ob_end_flush(); // Flush the output buffer if we get here
?>
