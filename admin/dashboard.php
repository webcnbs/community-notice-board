<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/AuditLog.php';

require_role(['admin']);

$categoryModel = new Category();
$categories = $categoryModel->all();

$auditLogModel = new AuditLog();
$logs = $auditLogModel->recent(10);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/MDstyle.css">
</head>

<body class="MDbody">

  <header class="MDheader">
    <h1>Admin Dashboard</h1>

    <nav class="MDnav">
      <a class="MDbtn" href="<?= BASE_URL ?>/route.php?action=manage-users">Users</a>
      <a class="MDbtn" href="<?= BASE_URL ?>/route.php?action=manage-categories">Categories</a>
      <a class="MDbtn" href="<?= BASE_URL ?>/route.php?action=manage-notices">Notices</a>
      <a class="MDbtn" href="<?= BASE_URL ?>/admin/manage-comments.php">Comments</a>
      <a class="MDbtn danger" href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
    </nav>
  </header>

  <h2 class="MDh2">Overview</h2>
  <p class="MDinfo">Total Categories: <?= count($categories) ?></p>

  <section class="card">
    <h3>Recent Audit Logs</h3>

    <?php if (empty($logs)): ?>
      <p>No logs found.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($logs as $log): ?>
          <li>
            <?= htmlspecialchars($log['action']) ?>
            by <?= htmlspecialchars($log['username'] ?? 'System') ?>
            at <?= htmlspecialchars($log['timestamp']) ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </section>

  <section class="card mt-2">
    <h3>Quick Links</h3>
   <p style="display:flex; flex-direction:column; align-items:center; gap:10px;">
  <a class="MDbtn secondary" style="width:50%; text-align:center;" href="<?= BASE_URL ?>/user/my-notices.php">
    My Notices
  </a>

  <a class="MDbtn secondary" style="width:50%; text-align:center;" href="<?= BASE_URL ?>/user/bookmarks.php">
    Bookmarks
  </a>

  <a class="MDbtn secondary" style="width:50%; text-align:center;" href="<?= BASE_URL ?>/user/profile.php">
    My Profile
  </a>
</p>

  </section>

  <footer class="MDfooter">
    © <?= date('Y') ?> Community Notice Board
  </footer>

</body>
</html>
