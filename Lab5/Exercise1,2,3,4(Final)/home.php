<?php
require_once 'includes/db_connect.php'; // db_connect.php starts session

// Define a default profile picture path for the welcome message if needed
$default_avatar_home = 'assets/default_avatar.png'; // Ensure this image exists
$user_profile_pic_home = $default_avatar_home;
$welcome_message = "Welcome to Our Digital Library!";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $welcome_message = "Welcome back, " . htmlspecialchars($_SESSION["username"]) . "!";
    // Optionally, fetch profile picture if logged in
    if (isset($_SESSION["user_id"])) {
        $stmt_home_user = $db->prepare("SELECT profile_picture FROM users WHERE id = ?");
        if ($stmt_home_user) {
            $stmt_home_user->bind_param("i", $_SESSION["user_id"]);
            $stmt_home_user->execute();
            $result_home_user = $stmt_home_user->get_result();
            if ($home_user_data = $result_home_user->fetch_assoc()) {
                if (!empty($home_user_data['profile_picture'])) {
                    $temp_pic_path = 'uploads/profile_pics/' . htmlspecialchars($home_user_data['profile_picture']);
                    if (file_exists($temp_pic_path)) {
                        $user_profile_pic_home = $temp_pic_path;
                    }
                }
            }
            $stmt_home_user->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Library System</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Specific styles for home.php if needed, or integrate into main style.css */
        body.home-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6dd5ed, #2193b0); /* Calming blue gradient */
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .home-container {
            background: rgba(0, 0, 0, 0.3); /* Semi-transparent dark overlay */
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 600px;
        }
        .home-logo {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #fff; /* White logo for contrast */
        }
        .home-welcome h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
            color: #fff;
        }
        .home-welcome p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            color: #e0e0e0; /* Lighter text for sub-message */
        }
        .home-actions .btn {
            margin: 10px;
            padding: 12px 25px;
            font-size: 1.1rem;
            min-width: 180px;
        }
        .btn-login-home {
            background-color: var(--primary-color); /* Use primary color from your theme */
            border-color: var(--primary-color);
            color: white;
        }
        .btn-login-home:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-library-home {
            background-color: var(--secondary-color); /* Use secondary color from your theme */
            border-color: var(--secondary-color);
            color: white;
        }
        .btn-library-home:hover {
            background-color: var(--secondary-dark);
            border-color: var(--secondary-dark);
        }
        .logged-in-user-avatar-home {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid rgba(255,255,255,0.5);
        }
    </style>
</head>
<body class="home-page">
    <div class="home-container">
        <i class="fas fa-book-reader home-logo"></i>
        <div class="home-welcome">
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            
            <?php endif; ?>
            <h1><?php echo $welcome_message; ?></h1>
            <p>Discover a world of knowledge and adventure. Access our extensive collection of books anytime, anywhere.</p>
        </div>

        <div class="home-actions">
            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <a href="dashboard.php?view=home" class="btn btn-library-home"><i class="fas fa-book-open"></i> Go to Library</a>
                <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-login-home"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="register.php" class="btn btn-secondary"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>