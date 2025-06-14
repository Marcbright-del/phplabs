<?php
// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT username, email, created_at, google_id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();


// Define a default profile picture path
$default_avatar = 'assets/default_avatar.png'; // You'll need to create this image or use a placeholder
$profile_pic_path = !empty($user['profile_picture']) ? 'uploads/profile_pics/' . htmlspecialchars($user['profile_picture']) : $default_avatar;

// Check if the user's profile picture file actually exists, otherwise use default
if (!empty($user['profile_picture']) && !file_exists($profile_pic_path)) {
    $profile_pic_path = $default_avatar; // Fallback to default if file is missing
}


?>


<section class="section section-profile">
    <h3><i class="fas fa-user-shield"></i> Your Profile</h3>
    <div class="profile-details">
        <p><strong><i class="fas fa-user"></i> Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong><i class="fas fa-calendar-check"></i> Member Since:</strong> <?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
        <?php if (!empty($user['google_id'])): ?>
        <p><strong class="text-success"><i class="fab fa-google"></i> Connected with Google</strong></p>
        <?php endif; ?>
        <!-- Add password change form or other profile actions here -->
    </div>
</section>

<!--<section class="section section-profile">
    <h3><i class="fas fa-user-shield"></i> Your Profile</h3>

    <div class="profile-grid">
        <div class="profile-picture-area">
            <img src="<?php echo $profile_pic_path; ?>" alt="<?php echo htmlspecialchars($user['username']); ?>'s Profile Picture" class="profile-img-large">
            <h4>Upload New Profile Picture</h4>
            <form action="user_actions.php?action=upload_profile_pic" method="post" enctype="multipart/form-data" class="styled-form">
                <div class="form-group">
                    <label for="profile_pic_file" class="sr-only">Choose file</label> {/* sr-only for accessibility if label is visually hidden */}
                    <input type="file" name="profile_pic_file" id="profile_pic_file" class="form-control-file" required accept="image/jpeg, image/png, image/gif">
                    <small class="form-text text-muted">Max file size: 2MB. Allowed types: JPG, PNG, GIF.</small>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload Picture</button>
            </form>
            <?php if (!empty($user['profile_picture']) && $profile_pic_path !== $default_avatar): ?>
                <form action="user_actions.php?action=remove_profile_pic" method="post" style="margin-top: 10px;">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove your profile picture?');">
                        <i class="fas fa-trash-alt"></i> Remove Picture
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="profile-details-area">
            <h4>Account Details</h4>
            <div class="profile-details">
                <p><strong><i class="fas fa-user"></i> Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong><i class="fas fa-calendar-check"></i> Member Since:</strong> <?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
                <?php if (!empty($user['google_id'])): ?>
                <p><strong class="text-success"><i class="fab fa-google"></i> Connected with Google</strong></p>
                <?php endif; ?>
            </div>
            {/* Add password change form or other profile actions here */}
        </div>
    </div>
</section>
                -->