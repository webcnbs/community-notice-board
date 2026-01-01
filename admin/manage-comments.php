<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

// 1. Security Check
require_role(['admin', 'manager']);

$commentModel = new Comment();
$feedback = ['type' => '', 'msg' => ''];

// 2. CSRF Token Generation (Security)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 3. Logic: Handle Moderation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $feedback = ['type' => 'error', 'msg' => 'Security token mismatch.'];
    } else {
        $commentId = (int)$_POST['comment_id'];
        $status = $_POST['status'] ?? '';
        
        if ($commentId && in_array($status, ['approved', 'rejected', 'pending'])) {
            if ($commentModel->moderate($commentId, $status)) {
                $feedback = ['type' => 'success', 'msg' => "Comment ID #$commentId is now: " . ucfirst($status)];
            } else {
                $feedback = ['type' => 'error', 'msg' => 'Database update failed.'];
            }
        }
    }
}

// 4. Fetch Data
$pendingComments = $commentModel->allPending();

// 5. Dynamic Navigation
$role = $_SESSION['user']['role'] ?? '';
$dashboardUrl = ($role === 'admin') ? 'dashboard.php' : '../index2.php';
?>
<?php $active = 'comments'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Comments | CNB</title>
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
                <a href="dashboard.php">Dashboard</a>
                <a href="manage-users.php">Manage Users</a>
                <a href="manage-categories.php">Manage Categories</a>
                <a href="manage-notices.php">Manage Notices</a>
                <a href="manage-comments.php">Manage Comments</a>

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
          <h2>Manage Comments</h2>
          <p>Panel</p>
        </div>
        <div class="top-actions">
          <div class="user-pill" title="Status">
            <span class="user-dot" aria-hidden="true"></span>
            <span>Online</span>
          </div>
        </div>
      </header>

      <div class="wrapper">
    <div class="page-header">
        <div>
            <h1>Manage Comments</h1>
            <p style="color: #6b7280;">Reviewing <?= count($pendingComments) ?> pending submissions</p>
        </div>
        <a href="<?= $dashboardUrl ?>" class="btn secondary">← Dashboard</a>
    </div>

    <?php if ($feedback['msg']): ?>
        <div class="alert alert-<?= $feedback['type'] ?>">
            <?= $feedback['msg'] ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($pendingComments)): ?>
        <?php foreach ($pendingComments as $c): ?>
            <div class="comment-card">
                <div class="meta-info">
                    <span><strong>👤 <?= htmlspecialchars($c['username']) ?></strong></span>
                    <span>📌 Notice: <em><?= htmlspecialchars($c['notice_title']) ?></em></span>
                    <span>🕒 <?= date('j M Y, g:i a', strtotime($c['created_at'])) ?></span>
                </div>
                
                <div class="comment-text">
                    <?= nl2br(htmlspecialchars($c['content'])) ?>
                </div>

                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="comment_id" value="<?= $c['comment_id'] ?>">
                    
                    <div class="action-bar">
                        <label for="status-<?= $c['comment_id'] ?>">Set Status:</label>
                        <select name="status" id="status-<?= $c['comment_id'] ?>">
                            <option value="pending" selected>🕒 Keep Pending</option>
                            <option value="approved" style="color: var(--success);">✅ Approve for Board</option>
                            <option value="rejected" style="color: var(--danger);">❌ Reject & Hide</option>
                        </select>
                        <button type="submit" class="btn-update">Update Status</button>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 50px; background: white; border-radius: 12px; color: #6b7280;">
            <h3>Great job!</h3>
            <p>No more comments waiting for review.</p>
        </div>
    <?php endif; ?>
</div>
    </main>
  </div>
  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
