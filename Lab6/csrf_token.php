<?php
/**
 * CSRF Token Generator and Validator
 * 
 * This script provides functions to generate and validate CSRF tokens
 * to protect forms against Cross-Site Request Forgery attacks.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generates a new CSRF token and stores it in the session
 * 
 * @param string $form_name Identifier for the specific form (optional)
 * @return string The generated CSRF token
 */
function generate_csrf_token($form_name = 'default') {
    // Generate a random token
    $token = bin2hex(random_bytes(32));
    
    // Store token in session with timestamp
    $_SESSION['csrf_tokens'][$form_name] = [
        'token' => $token,
        'time' => time()
    ];
    
    return $token;
}

/**
 * Validates a submitted CSRF token against the stored token
 * 
 * @param string $token The token to validate
 * @param string $form_name Identifier for the specific form (optional)
 * @param int $expiry_time Token expiry time in seconds (default: 3600)
 * @return bool True if token is valid, false otherwise
 */
function validate_csrf_token($token, $form_name = 'default', $expiry_time = 3600) {
    // Check if token exists in session
    if (!isset($_SESSION['csrf_tokens'][$form_name])) {
        return false;
    }
    
    $stored = $_SESSION['csrf_tokens'][$form_name];
    
    // Check if token has expired
    if (time() - $stored['time'] > $expiry_time) {
        // Remove expired token
        unset($_SESSION['csrf_tokens'][$form_name]);
        return false;
    }
    
    // Validate token
    if (hash_equals($stored['token'], $token)) {
        // Remove used token (one-time use)
        unset($_SESSION['csrf_tokens'][$form_name]);
        return true;
    }
    
    return false;
}

/**
 * Outputs a hidden input field with CSRF token
 * 
 * @param string $form_name Identifier for the specific form (optional)
 * @return string HTML for the hidden input field
 */
function csrf_token_input($form_name = 'default') {
    $token = generate_csrf_token($form_name);
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}
?>