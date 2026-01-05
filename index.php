<?php
require_once __DIR__ . '/includes/config.php';
session_name(SESSION_NAME);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Community Notice Board</title>
<link href="<?= BASE_URL ?>/assets/css/Nstyle.css" rel="stylesheet"></head>
<body>
  
  <header class="LRheader">
    <h1> <a href="index.php"> <img class="LRlogo" src="assets/images/logo.png" alt=""> Grand Nova </a></h1>
    <nav>
      <?php if (!empty($_SESSION['user'])): ?>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
        <a href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
        <?php else: ?>
        <a href="<?= BASE_URL ?>/login.php">Login</a>
        <a href="<?= BASE_URL ?>/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </header>

  <div class="container">
    <div class="filters">
      <select id="filter-category">
        <option value="">All Categories</option>
        <option value="1">Events</option>
        <option value="2">Emergencies</option>
        <option value="3">Maintenance</option>
        <option value="4">General</option>
      </select>
      <select id="filter-priority">
        <option value="">All Priorities</option>
        <option>High</option>
        <option>Medium</option>
        <option>Low</option>
      </select>
      <input type="text" id="filter-q" placeholder="Search notices...">
      <button id="filter-go">Apply</button>
      
      <button id="filter-clear" style="background: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; margin-left: 5px;">Clear</button>
    </div>

    <div id="results-meta" style="display: none; margin-bottom: 15px; font-size: 0.9em; color: #666;">
        Found <span id="results-count" style="font-weight: bold; color: #2563eb;">0</span> matching notices.
    </div>

    <ul id="notice-list"></ul>

  <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'resident'): ?>
    <p class="mt-2">
      <a href="user/profile.php" class="btn">👤 My Profile</a>
      <a href="user/bookmarks.php" class="btn">🔖 My Bookmarks</a>
    </p>
  <?php endif; ?>
<!-- 
  <script src="assets/js/ajax.js"></script> -->
<script type="module" src="<?= BASE_URL ?>/assets/js/ajax.js"></script>  
</body>
</html>