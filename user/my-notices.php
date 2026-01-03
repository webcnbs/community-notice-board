<?php
// user/my-notices.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/database.php';

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

require_once __DIR__ . '/../models/Notice.php';

$pdo = Database::getInstance()->pdo();
$stmt = $pdo->prepare("SELECT * FROM notices WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user']['user_id']]);
$myNotices = $stmt->fetchAll();

// Back link logic
$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = '../admin/dashboard.php';
} elseif ($role === 'manager') {
    $dashboardUrl = '../index2.php';
} else {
    $dashboardUrl = '../index.php';
}
?>
<?php $active = 'my_notices'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Notices | CNB</title>
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
          <h2>My Notices</h2>
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>My Notices</h2>
        <a href="create-notice.php" class="btn">+ New Notice</a>
    </div>

    <?php if (empty($myNotices)): ?>
        <div class="form-card" style="text-align: center;">
            <p class="muted">You haven't created any notices yet.</p>
            <a href="create-notice.php" class="btn mt-2">Post your first notice</a>
        </div>
    <?php else: ?>
        <div class="notice-grid">
            <?php foreach ($myNotices as $n): ?>
                <?php 
                    $priorityClass = 'priority-' . strtolower($n['priority']);
                ?>
                <div class="notice-card">
                    <span class="priority-badge <?= $priorityClass ?>">
                        <?= htmlspecialchars($n['priority']) ?>
                    </span>
                    
                    <a href="../view-notice.php?id=<?= $n['notice_id']; ?>" class="notice-title">
                        <?= htmlspecialchars($n['title']); ?>
                    </a>
                    
                    <div class="notice-date">
                        📅 <?= date('M d, Y', strtotime($n['created_at'])); ?>
                    </div>

                    <div class="card-actions">
                        <a href="edit-notice.php?id=<?= $n['notice_id']; ?>" class="btn" style="padding: 5px 12px; font-size: 0.8rem;">Edit</a>
                        <form action="delete-notice-handler.php" method="POST" onsubmit="return confirm('Delete this notice?');" style="display:inline;">
                            <input type="hidden" name="notice_id" value="<?= $n['notice_id']; ?>">
                            <button type="submit" class="btn secondary" style="padding: 5px 12px; font-size: 0.8rem; color: var(--danger);">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <p class="mt-3">
        <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back to Dashboard</a>
    </p>
</div>
    </main>
  </div>
  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
