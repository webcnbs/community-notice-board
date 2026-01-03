<?php
// user/profile.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = '../admin/dashboard.php';
} elseif ($role === 'manager') {
    $dashboardUrl = '../index2.php';
} else {
    $dashboardUrl = '../index.php';
}

$username = $_SESSION['user']['username'] ?? 'User';
$initial = strtoupper(substr($username, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile | CNB</title>

  <!-- Keep your base + admin theme (theme LAST so it wins) -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin-theme.css">
</head>
<body>

<div class="dashboard-container">

  <!-- Sidebar (same style as your admin panel) -->
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
    <?php if (isset($_GET['success'])): ?>
      <div class="alert success">Profile updated successfully!</div>
    <?php endif; ?>

    <!-- Centered profile card -->
    <div class="profile-page">
      <section class="card profile-card" aria-label="Profile details">
        <div class="card-body">

          <div class="profile-header">
            <div class="profile-avatar" aria-hidden="true"><?= htmlspecialchars($initial) ?></div>
            <h2 class="profile-title">User Profile</h2>
            <p class="profile-subtitle">Manage your account details</p>
          </div>

          <div class="profile-meta">
            <div class="profile-info-row">
              <span class="label">Username</span>
              <span class="value"><?= htmlspecialchars($username); ?></span>
            </div>

            <div class="profile-info-row">
              <span class="label">Access Level</span>
              <span class="value"><span class="role-badge"><?= htmlspecialchars($role ?: 'user'); ?></span></span>
            </div>

            <div class="profile-info-row">
              <span class="label">Status</span>
              <span class="value"><span class="status-active">Active</span></span>
            </div>
          </div>

          <div class="profile-actions">
            <a href="edit-profile.php" class="btn">Edit Profile Settings</a>
            <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back to Dashboard</a>
          </div>

        </div>
      </section>
    </div>

  </main>

</div>

</body>
</html>
