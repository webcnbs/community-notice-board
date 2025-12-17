<?php
// index2.php — Manager Dashboard
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

// session_name(SESSION_NAME);
// session_start();

require_role(['manager']); // restrict to managers only
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h2>
  <p class="info">This is your manager dashboard. Use the shortcuts below to manage notices, categories, and your profile.</p>

  <div class="dashboard-grid">
    <a href="admin/manage-notices.php" class="btn">📢 Create or Manage Notices</a>
    <a href="user/my-notices.php" class="btn">📝 My Notices</a>
    <a href="user/bookmarks.php" class="btn">🔖 View My Bookmarks</a>
    <a href="admin/manage-categories.php" class="btn">📂 Manage Categories</a>
    <a href="admin/manage-comments.php" class="btn">💬 Moderate Comments</a>
    <a href="user/profile.php" class="btn">👤 My Profile</a>
    <a href="route.php?action=logout" class="btn danger">🚪 Logout</a>
  </div>

  <style>
    .dashboard-grid {
      display: grid;
      gap: 1rem;
      margin-top: 2rem;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
    .btn {
      display: block;
      padding: 1rem;
      background: var(--primary, #007bff);
      color: white;
      text-align: center;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
    }
    .btn.danger {
      background: #dc3545;
    }
  </style>
</body>
</html>