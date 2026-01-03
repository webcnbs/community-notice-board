<?php
// user/edit-profile.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

$user = $_SESSION['user'];
$feedback = "";

// Logic to handle the form submission would go here (connecting to a Controller or Model)
?>
<?php $active = 'edit_profile'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile | CNB</title>
  <link rel="stylesheet" href="../assets/css/admin-theme.css">
</head>
<body>
  <div class="dashboard-container">
     <aside class="sidebar" aria-label="Admin navigation">
            <div class="brand">
                <div class="brand-mark">CN</div>
                <div>
                    <h3>CNB Admin</h3>
                    <small>Control panel</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                 <a href="../admin/dashboard.php">Dashboard</a>
                <a href="../admin/manage-users.php">Manage Users</a>
                <a href="../admin/manage-categories.php">Manage Categories</a>
                <a href="../admin/manage-notices.php">Manage Notices</a>
                <a href="../admin/manage-comments.php">Manage Comments</a>

                <div class="nav-divider">User Space</div>
                <a href="../user/profile.php">My Profile</a>
                <a href="../user/my-notices.php">My Notices</a>
                <a href="../user/bookmarks.php">Bookmarks</a>
                <a href="../route.php?action=logout" class="logout-btn">Logout</a>
            </nav>
        </aside>

    <main class="main-content">
      
      <header class="top-bar">
        <div class="page-title">
          <h2>Edit Profile</h2>
          <p>Panel</p>
        </div>
        <div class="top-actions">
          <div class="user-pill" title="Status">
            <span class="user-dot" aria-hidden="true"></span>
            <span>Online</span>
          </div>
        </div>
      </header>

      <div class="container">
    <div class="form-card">
        <h2>Edit Profile</h2>
        <p class="muted">Update your account information below.</p>
        
        <form id="editProfileForm" action="update-logic.php" method="POST">
            <div class="mt-2">
                <label>Username (Cannot be changed)</label>
                <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>
            </div>

            <div class="mt-2">
                <label>Email Address</label>
                <input type="email" name="email" id="email" placeholder="enter@email.com" required>
            </div>

            <div class="mt-2">
                <label>New Password</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current">
            </div>

            <div class="mt-2">
                <label>Confirm New Password</label>
                <input type="password" id="confirm_password" placeholder="Re-type password">
                <small id="passError" style="color: var(--danger); display: none;">Passwords do not match!</small>
            </div>

            <div class="mt-3" style="display: grid; gap: 10px;">
                <button type="submit" class="btn" id="submitBtn">Save Changes</button>
                <a href="profile.php" class="btn secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/main.js" defer></script>
    </main>
  </div>
  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
