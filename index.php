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
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
  
  <header>
    <h1>Community Notice Board</h1>
    <nav>
      <?php if (!empty($_SESSION['user'])): ?>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
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
      <input type="text" id="filter-q" placeholder="Search...">
      <button id="filter-go">Apply</button>
    </div>
    <ul id="notice-list"></ul>
  </div>

  <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'resident'): ?>
    <p class="mt-2">
      <a href="user/profile.php" class="btn">👤 My Profile</a>
      <a href="user/bookmarks.php" class="btn">🔖 My Bookmarks</a>
    </p>
  <?php endif; ?>

  <script src="assets/js/ajax.js"></script>
</body>
</html>