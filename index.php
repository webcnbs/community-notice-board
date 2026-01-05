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
  <link href="<?= BASE_URL ?>/assets/css/Nstyle.css" rel="stylesheet">
  <style>
    /* ===================== DARK MODE ===================== */
body.dark-mode {
  background: #37353E !important;
  color: #e0e0e0 !important;
}

/* Header */
body.dark-mode header {
  background: #69497eff !important;
  border-bottom: 1px solid #444 !important;
  color: #ffffff !important;
  box-shadow: 0 8px 20px #655b61ff !important;
}

body.dark-mode header h1{
  border-bottom: 5px solid #444 !important;
}
body.dark-mode header h1 a {
  color: #ffffff !important;
}

body.dark-mode nav a {
  color: #d19cf4 !important;
}
body.dark-mode nav a:hover {
  text-decoration: underline !important;
  color: #ffffff !important;
}

/* Container & Cards */

body.dark-mode .card,
body.dark-mode .form-card,
body.dark-mode .notice-item,
body.dark-mode form {
  background: #2a2a2a !important;
  border: 1px solid #555 !important;
  color: #e0e0e0 !important;
  box-shadow: 0 2px 6px #B13BFF !important;
}
body.dark-mode .container{
  background: #69497eff !important;
  border: 1px solid #555 !important;
  color: #e0e0e0 !important;
  box-shadow: 0 2px 6px #B13BFF!important;
}

/* Filters */
body.dark-mode .filters select,
body.dark-mode .filters input {
  background: #1f1f1f !important;
  border: 1px solid #555 !important;
  color: #e0e0e0 !important;
}
body.dark-mode .filters button {
  background: #a02d6f !important;
  color: #e0e0e0 !important;
}
body.dark-mode .filters button:hover {
  background: #d19cf4 !important;
  color: #414040 !important;
}

/* Buttons */
body.dark-mode button,
body.dark-mode .btn {
  background: #a02d6f !important;
  color: #e0e0e0 !important;
  box-shadow: 0 6px 15px #B13BFF !important;
}
body.dark-mode button:hover,
body.dark-mode .btn:hover {
  background: #d19cf4 !important;
  color: #414040 !important;
  box-shadow: 0 10px 20px #e0e0e0 !important;
}
body.dark-mode button.danger {
  background: #a83232 !important;
}
body.dark-mode .btn.secondary {
  background: #555 !important;
  color: #e0e0e0 !important;
}

/* Info text */
body.dark-mode .info {
  background: #1f1f1f !important;
  color: #cccccc !important;
  border-left: 3px solid #a02d6f !important;
}

/* Notice list */
body.dark-mode #notice-list li {
  border-bottom: 1px solid #555 !important;
}
body.dark-mode #notice-list li a {
  color: #d19cf4 !important;
}
body.dark-mode #notice-list small {
  color: #aaa !important;
}

/* Comments */
body.dark-mode .comment {
  background: #2a2a2a !important;
  border: 1px solid #555 !important;
  color: #e0e0e0 !important;
}
body.dark-mode .comment strong {
  color: #d19cf4 !important;
}
body.dark-mode .comment small {
  color: #aaa !important;
}

/* Inputs & Textarea */
body.dark-mode input[type="text"],
body.dark-mode input[type="email"],
body.dark-mode input[type="password"],
body.dark-mode input[type="date"],
body.dark-mode select,
body.dark-mode textarea {
  background: #1f1f1f !important;
  border: 1px solid #555 !important;
  color: #e0e0e0 !important;
}
body.dark-mode input:focus,
body.dark-mode textarea:focus,
body.dark-mode select:focus {
  border-color: #a02d6f !important;
  box-shadow: 0 0 0 3px rgba(177,59,255,0.25) !important;
}
  </style>
</head>
<body>
  
  <header class="LRheader">
    <h1>
      <a href="index.php">
        <img class="LRlogo" src="assets/images/logo.png" alt=""> Grand Nova
      </a>
    </h1>
    <nav>
      <?php if (!empty($_SESSION['user'])): ?>
        <span>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
        <a href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>/login.php">Login</a>
        <a href="<?= BASE_URL ?>/register.php">Register</a>
      <?php endif; ?>
      <!-- Dark mode toggle button -->
      <button id="toggle-dark" class="btn">🌙</button>
    </nav>
  </header>
 <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'resident'): ?>
    <p class="mt-2">
      <a href="user/profile.php" class="btn">👤 My Profile</a>
      <a href="user/bookmarks.php" class="btn">🔖 My Bookmarks</a>
    </p>
  <?php endif; ?>
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

 

  <script type="module" src="<?= BASE_URL ?>/assets/js/ajax.js"></script>

  <!-- Dark mode script -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toggleBtn = document.getElementById("toggle-dark");
      const body = document.body;

      // Apply saved preference
      if (localStorage.getItem("theme") === "dark") {
        body.classList.add("dark-mode");
        toggleBtn.textContent = "☀️";
      } else if (!localStorage.getItem("theme")) {
        // Default to OS theme
        if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
          body.classList.add("dark-mode");
          toggleBtn.textContent = "☀️";
        }
      }

      // Toggle on click
      toggleBtn.addEventListener("click", function() {
        body.classList.toggle("dark-mode");
        if (body.classList.contains("dark-mode")) {
          localStorage.setItem("theme", "dark");
          toggleBtn.textContent = "☀️";
        } else {
          localStorage.setItem("theme", "light");
          toggleBtn.textContent = "🌙";
        }
      });
    });
  </script>
</body>
</html>