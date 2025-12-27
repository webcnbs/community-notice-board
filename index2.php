<?php
// index2.php — Manager Dashboard
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';

require_role(['manager']); // restrict to managers only
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/MDstyle.css">
</head>
<body class="MDbody">
  <header class="MDheader">
    <h1>Community Notice Board</h1>
    <nav class="MDnav">
      <a href="route.php?action=logout" class="MDbtn danger">Logout</a>
    </nav>
  </header>

  <h2 class="MDh2">Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h2>
  <p class="MDinfo">This is your manager dashboard. Use the shortcuts below to manage notices, categories, and your profile.</p>

  <div class="MDdashboard-grid">
    <a href="admin/manage-notices.php" class="MDbtn"><span class="icon">📢</span> Create or Manage Notices</a>
    <a href="user/my-notices.php" class="MDbtn"><span class="icon">📝</span> My Notices</a>
    <a href="user/bookmarks.php" class="MDbtn"><span class="icon">🔖</span> View My Bookmarks</a>
    <a href="admin/manage-categories.php" class="MDbtn"><span class="icon">📂</span> Manage Categories</a>
    <a href="admin/manage-comments.php" class="MDbtn"><span class="icon">💬</span> Moderate Comments</a>
    <a href="user/profile.php" class="MDbtn"><span class="icon">👤</span> My Profile</a>
  </div>

  <footer class="MDfooter">
    Manager Dashboard © <?= date('Y') ?> — Community Notice Board
  </footer>

  <script>
    // Simple JS animation: fade-in effect for buttons
    document.querySelectorAll('.MDbtn').forEach((MDbtn, i) => {
      MDbtn.style.opacity = 0;
      MDbtn.style.transform = 'translateY(20px)';
      setTimeout(() => {
        MDbtn.style.transition = 'all 0.6s ease';
        MDbtn.style.opacity = 1;
        MDbtn.style.transform = 'translateY(0)';
      }, i * 150);
    });
  </script>
</body>
</html>