<?php
require_once 'includes/db_connect.php'; // db_connect.php already starts session
$username_err = $password_err = $login_err = "";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php"); // Redirect if already logged in
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?"; // Allow login with username or email
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("ss", $param_username, $param_username_as_email);
            $param_username = $username;
            $param_username_as_email = $username; // Use the same input for both checks

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $db_username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            // session_start(); // Already started in db_connect.php
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $db_username;

                            // Redirect to originally requested page or dashboard
                            $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'dashboard.php';
                            unset($_SESSION['redirect_url']);
                            header("location: " . $redirect_url);
                            exit;
                        } else {
                            $login_err = "Invalid username/email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid username/email or password.";
                }
            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
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
    <title>Login - Library System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-book-reader logo-icon"></i>
                <h2>Welcome Back!</h2>
                <p>Login to access the library dashboard</p>
            </div>
            <?php if(!empty($login_err)): ?>
                <div class="message error"><i class="fas fa-exclamation-triangle"></i> <?php echo $login_err; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="styled-form">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label><i class="fas fa-user"></i> Username or Email</label>
                    <input type="text" name="username" class="form-control" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Login</button>
                </div>
                <p class="auth-switch">Don't have an account? <a href="register.php">Register here</a></p>
            </form>
            <div class="social-login">
                <p>Or login with</p>
                <a href="google_login_init.php" class="btn btn-google btn-block"><i class="fab fa-google"></i> Login with Google</a>
            </div>
        </div>
    </div>
</body>
</html>