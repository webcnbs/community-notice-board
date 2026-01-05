<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

require_role(['admin', 'manager']);

$commentModel = new Comment();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = (int)($_POST['comment_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($commentId && in_array($status, ['approved', 'rejected', 'pending'])) {
        $commentModel->moderate($commentId, $status);
    }

    // simple redirect to avoid resubmission
    header("Location: " . BASE_URL . "/admin/manage-comments.php");
    exit;
}

$pendingComments = $commentModel->allPending();

$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = BASE_URL . '/route.php?action=admin-dashboard';
} elseif ($role === 'manager') {
    $dashboardUrl = BASE_URL . '/index2.php';
} else {
    $dashboardUrl = BASE_URL . '/index.php';
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Comments</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/MDstyle.css">
</head>

<body class="MDbody">

  <header class="MDheader">
    <h1>Manage Comments</h1>

    <nav class="MDnav">
      <a class="MDbtn secondary" href="<?= $dashboardUrl; ?>">Back</a>
      <a class="MDbtn danger" href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
    </nav>
  </header>

  <div class="container">

    <section class="card">
      <h2 style="margin-top:0;">Pending Comments</h2>

      <?php if (!empty($pendingComments)): ?>
        <div class="comments">
          <?php foreach ($pendingComments as $c): ?>
            <div class="comment">
              <p>
                <strong><?= htmlspecialchars($c['username']) ?></strong>
                on <em><?= htmlspecialchars($c['notice_title']) ?></em>
              </p>

              <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
              <small>Posted at <?= htmlspecialchars($c['created_at']) ?></small>

              <form method="post" style="margin-top:10px;">
                <input type="hidden" name="comment_id" value="<?= (int)$c['comment_id'] ?>">
                <select name="status">
                  <option value="approved">Approve</option>
                  <option value="rejected">Reject</option>
                  <option value="pending" selected>Keep Pending</option>
                </select>
                <button type="submit">Update</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No pending comments to review.</p>
      <?php endif; ?>

    </section>

  </div>

</body>
</html>