<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/AuditLog.php';

// Restrict access to admins only
require_role(['admin']);

// Fetch categories
$categoryModel = new Category();
$categories = $categoryModel->all(); // assumes you have an all() method

// Fetch recent audit logs
$auditLogModel = new AuditLog();
$logs = $auditLogModel->recent(10); // fetch last 10 logs
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>Admin Dashboard</h2>
  <section class="card">
    <h3>System Overview</h3>
    <p>Total Categories: <?php echo count($categories); ?></p>
    <p>Recent Audit Logs:</p>
    <ul>
      <?php foreach ($logs as $log): ?>
        <li>
          <?php echo htmlspecialchars($log['action']); ?>
          by <?php echo htmlspecialchars($log['username'] ?? 'System'); ?>
          at <?php echo $log['timestamp']; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<nav>
  <a href="manage-users.php">Manage Users</a>
  <a href="manage-categories.php">Manage Categories</a>
  <a href="manage-notices.php">Manage Notices</a>
  <a href="manage-comments.php">Manage Comments</a> <!-- ✅ Add this -->
  <a href="../user/profile.php">My Profile</a>
  <a href="../user/my-notices.php">My Notices</a>
  <a href="../user/bookmarks.php">Bookmarks</a>
  <a href="../route.php?action=logout" class="btn danger">Logout</a>
</nav>
</body>
</html>