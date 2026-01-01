<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Notice.php';
require_once __DIR__ . '/models/Bookmark.php';
require_once __DIR__ . '/models/Comment.php';

session_name(SESSION_NAME);
session_start();

$notice = new Notice();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "Invalid notice ID.";
    exit;
}

$data = $notice->find($id);
if (!$data) {
    echo "Notice not found.";
    exit;
}

$notice->incrementViews($id);

// ✅ Check if logged in and already bookmarked
$isBookmarked = false;
if (is_logged_in()) {
    $bookmarkModel = new Bookmark();
    $isBookmarked = $bookmarkModel->exists($_SESSION['user']['user_id'], $id);
}

// ✅ Fetch approved comments
$commentModel = new Comment();
$comments = $commentModel->listApproved($id);

// Back URL (same logic you had)
$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = 'admin/dashboard.php';
} elseif ($role === 'manager') {
    $dashboardUrl = 'index2.php';
} else {
    $dashboardUrl = 'index.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($data['title']) ?> | CNB</title>

  <!-- Keep base + admin theme (theme last so it wins) -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/admin-theme.css">
</head>

<body>
<div class="dashboard-container">

  <!-- ✅ Unified Sidebar (SAME everywhere) -->
  <aside class="sidebar" aria-label="Navigation">
    <div class="brand">
      <div class="brand-mark">CN</div>
      <div>
        <h3>CNB</h3>
        <small>Control panel</small>
      </div>
    </div>

    <nav class="sidebar-nav">
      <?php if (($role ?? '') === 'admin'): ?>
        <a href="admin/dashboard.php">Dashboard</a>
        <a href="admin/manage-users.php">Manage Users</a>
        <a href="admin/manage-categories.php">Manage Categories</a>
        <a href="admin/manage-notices.php">Manage Notices</a>
        <a href="admin/manage-comments.php">Manage Comments</a>

        <div class="nav-divider">User Space</div>
        <a href="user/profile.php">My Profile</a>
        <a href="user/my-notices.php">My Notices</a>
        <a href="user/bookmarks.php">Bookmarks</a>
        <a href="route.php?action=logout" class="logout-btn">Logout</a>

      <?php else: ?>
        <a href="user/profile.php">Profile</a>
        <a href="user/my-notices.php">My Notices</a>
        <a href="user/bookmarks.php">Bookmarks</a>

        <?php if (($role ?? '') === 'manager'): ?>
          <a href="admin/manage-comments.php">Manage Comments</a>
        <?php endif; ?>

        <div class="nav-divider">Account</div>
        <a href="user/edit-profile.php">Edit Profile</a>
        <a href="route.php?action=logout" class="logout-btn">Logout</a>
      <?php endif; ?>
    </nav>
  </aside>

  <!-- ✅ Main Content -->
  <main class="main-content">

    <div class="notice-page">
      <section class="card notice-card">
        <div class="card-body">

          <!-- Title + subtitle -->
          <div class="notice-head">
            <h1 class="notice-title"><?= htmlspecialchars($data['title']) ?></h1>
            <p class="notice-sub muted">
              Posted: <?= htmlspecialchars($data['created_at']) ?>
              <?php if (!empty($data['expiry_date'])): ?>
                • Expires: <?= htmlspecialchars($data['expiry_date']) ?>
              <?php endif; ?>
            </p>
          </div>

          <!-- Meta pills -->
          <div class="notice-meta">
            <span class="meta-pill">
              <span class="meta-label">Category</span>
              <span class="meta-value"><?= htmlspecialchars($data['category_name']) ?></span>
            </span>

            <span class="meta-pill">
              <span class="meta-label">Priority</span>
              <span class="meta-value"><?= htmlspecialchars($data['priority']) ?></span>
            </span>
          </div>

          <!-- Content -->
          <div class="notice-content">
            <?= nl2br(htmlspecialchars($data['content'])) ?>
          </div>

          <!-- Actions -->
          <div class="notice-actions mt-3">
            <?php if (is_logged_in()): ?>
              <form method="post" action="api/bookmarks.php" class="inline-form">
                <input type="hidden" name="notice_id" value="<?= $id ?>">
                <?php if ($isBookmarked): ?>
                  <button type="submit" name="action" value="remove" class="btn danger">Remove Bookmark</button>
                <?php else: ?>
                  <button type="submit" name="action" value="add" class="btn">Bookmark</button>
                <?php endif; ?>
              </form>
            <?php else: ?>
              <div class="alert info">Login to bookmark this notice.</div>
            <?php endif; ?>

            <a href="<?= $dashboardUrl; ?>" class="btn secondary">← Back</a>
          </div>

          <hr class="hr">

          <!-- Comments -->
          <div class="comments-wrap">
            <h3 class="comments-title">Comments</h3>

            <div class="comment-list">
              <?php if ($comments): ?>
                <?php foreach ($comments as $c): ?>
                  <div class="comment-item">
                    <div class="comment-top">
                      <strong class="comment-user"><?= htmlspecialchars($c['username']); ?></strong>
                      <small class="comment-time muted"><?= htmlspecialchars($c['created_at']); ?></small>
                    </div>
                    <div class="comment-body">
                      <?= nl2br(htmlspecialchars($c['content'])); ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="muted">No comments yet.</p>
              <?php endif; ?>
            </div>

            <?php if (is_logged_in()): ?>
              <form method="post" action="controllers/CommentController.php" class="comment-form mt-2">
                <input type="hidden" name="notice_id" value="<?= $id ?>">
                <label for="comment-content" class="muted">Add a comment</label>
                <textarea id="comment-content" name="content" required placeholder="Write your comment..."></textarea>
                <div class="mt-2">
                  <button type="submit" name="action" value="add" class="btn">Add Comment</button>
                </div>
              </form>
            <?php else: ?>
              <div class="alert info mt-2">Login to add a comment.</div>
            <?php endif; ?>
          </div>

        </div>
      </section>
    </div>

  </main>
</div>

<script>
  // Soft entrance animation
  document.addEventListener('DOMContentLoaded', () => {
    const card = document.querySelector('.notice-card');
    if (!card) return;
    card.style.opacity = '0';
    card.style.transform = 'translateY(10px)';
    card.style.transition = 'all 240ms ease-out';
    requestAnimationFrame(() => {
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    });
  });
</script>

</body>
</html>
