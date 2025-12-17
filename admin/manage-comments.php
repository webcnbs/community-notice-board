<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

require_role(['admin', 'manager']);

$commentModel = new Comment();

// Handle moderation actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = (int)$_POST['comment_id'];
    $status = $_POST['status'] ?? '';
    if ($commentId && in_array($status, ['approved', 'rejected', 'pending'])) {
        $commentModel->moderate($commentId, $status);
    }
}

// Fetch all pending comments
$pendingComments = $commentModel->allPending();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Comments</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>Manage Comments</h2>

  <?php if ($pendingComments): ?>
    <div class="comment-list">
      <?php foreach ($pendingComments as $c): ?>
        <div class="comment-item">
          <p><strong><?= htmlspecialchars($c['username']) ?></strong> on 
             <em><?= htmlspecialchars($c['notice_title']) ?></em>:</p>
          <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
          <small>Posted at <?= htmlspecialchars($c['created_at']) ?></small>

          <form method="post" style="margin-top: 5px;">
            <input type="hidden" name="comment_id" value="<?= $c['comment_id'] ?>">
            <select name="status">
              <option value="approved">Approve</option>
              <option value="rejected">Reject</option>
              <option value="pending" selected>Keep Pending</option>
            </select>
            <button type="submit">Update</button>
          </form>
        </div>
        <hr>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No pending comments to review.</p>
  <?php endif; ?>

<?php
$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = 'dashboard.php';
} elseif ($role === 'manager') {
    $dashboardUrl = '../index2.php';
} else {
    $dashboardUrl = '../index.php';
}
?>
<p class="mt-2">
  <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back to Dashboard</a>
</p></body>
</html>