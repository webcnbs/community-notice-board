<?php
// user/bookmarks.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Bookmark.php';

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

$bookmarkModel = new Bookmark();
$bookmarks = $bookmarkModel->list($_SESSION['user']['user_id']);

// Determine the back link based on role
$role = $_SESSION['user']['role'] ?? '';
$dashboardUrl = ($role === 'admin') ? '../admin/dashboard.php' : (($role === 'manager') ? '../index2.php' : '../index.php');
?>
<?php $active = 'bookmarks'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bookmarks | CNB</title>
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
          <h2>Bookmarks</h2>
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
    <div style="margin-bottom: 2rem;">
        <h2>Saved Bookmarks</h2>
        <p style="color: var(--muted);">Quickly access the notices you've saved.</p>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">✅ Notice bookmarked successfully!</div>
    <?php endif; ?>
    <?php if (isset($_GET['removed'])): ?>
        <div class="alert alert-success">🗑️ Bookmark removed successfully!</div>
    <?php endif; ?>

    <?php if (empty($bookmarks)): ?>
        <div class="form-card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🔖</div>
            <p class="muted">You haven't bookmarked any notices yet.</p>
            <a href="../index.php" class="btn mt-2">Browse the Board</a>
        </div>
    <?php else: ?>
        <div class="bookmark-grid">
            <?php foreach ($bookmarks as $b): ?>
                <div class="bookmark-card">
                    <div>
                        <a href="../view-notice.php?id=<?= $b['notice_id']; ?>" class="bookmark-title">
                            <?= htmlspecialchars($b['title']); ?>
                        </a>
                        <div class="bookmark-date">
                            📅 Saved on <?= date('M d, Y', strtotime($b['created_at'])); ?>
                        </div>
                    </div>

                    <div class="bookmark-actions">
                        <a href="../view-notice.php?id=<?= $b['notice_id']; ?>" class="btn-view">Read Notice</a>
                        <form method="post" action="../api/bookmarks.php" style="margin-left: auto;">
                            <input type="hidden" name="notice_id" value="<?= $b['notice_id']; ?>">
                            <button type="submit" name="action" value="remove" class="btn secondary" 
                                    style="padding: 5px 12px; font-size: 0.75rem; color: var(--danger);"
                                    onclick="return confirm('Remove this bookmark?')">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mt-3">
        <a href="<?= $dashboardUrl; ?>" class="btn secondary">← Back to Dashboard</a>
    </div>
</div>
    </main>
  </div>
  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
