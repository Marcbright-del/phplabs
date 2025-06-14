<?php
require_once 'includes/db_connect.php';
$username_err = $email_err = $password_err = $register_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("sss", $param_username, $param_email, $param_password);
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            if ($stmt->execute()) {
                $register_success = "Registration successful! You can now <a href='login.php'>login</a>.";
                // Optionally, log the user in directly:
                // $_SESSION["loggedin"] = true;
                // $_SESSION["id"] = $db->insert_id; // Get last inserted ID
                // $_SESSION["username"] = $username;
                // header("location: dashboard.php");
                // exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Library System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-book-reader logo-icon"></i>
                <h2>Create Account</h2>
                <p>Join our digital library</p>
            </div>
            <?php if(!empty($register_success)): ?>
                <div class="message success"><i class="fas fa-check-circle"></i> <?php echo $register_success; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="styled-form">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> Register</button>
                </div>
                <p class="auth-switch">Already have an account? <a href="login.php">Login here</a></p>
            </form>
             <div class="social-login">
                <p>Or register with</p>
                <a href="google_login_init.php" class="btn btn-google btn-block"><i class="fab fa-google"></i> Register with Google</a>
            </div>
        </div>
    </div>
</body>
</html>