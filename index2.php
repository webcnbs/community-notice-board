<?php
// index2.php — Manager Dashboard

// Include the global configuration file (database settings, constants, etc.)
require_once __DIR__ . '/includes/config.php';

// Include session management (starts/resumes session, handles login state)
require_once __DIR__ . '/includes/session.php';

// Include helper functions (utility functions used across the project)
require_once __DIR__ . '/includes/functions.php';

// Restrict access: only users with the "manager" role can view this page.
// If a non-manager tries to access, they will be redirected or denied.
require_role(['manager']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Define character encoding -->
  <meta charset="UTF-8">
  <!-- Page title shown in browser tab -->
  <title>Manager Dashboard</title>
  <!-- Link to global styles -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Link to manager dashboard-specific styles -->
  <link rel="stylesheet" href="assets/css/MDstyle.css">
</head>
<body class="MDbody">
  <!-- Header section at the top of the dashboard -->
  <header class="MDheader">
    <!-- Title of the application -->
    <h1> Grand Nova </h1>
    <!-- Navigation bar inside the header -->
    <nav class="MDnav">
      <!-- Dark mode toggle button (🌙 by default, switches to ☀️ when active) -->
      <button id="toggle-dark" class="MDbtn">🌙</button>
      <!-- Logout button that calls route.php with action=logout -->
      <a href="route.php?action=logout" class="MDbtn danger">Logout</a>
    </nav>
  </header>

  <div style="padding: 10% 15% 10% 15%;">

  <!-- Personalized welcome message using the logged-in user's username -->
  <h2 class="MDh2">Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h2>

  <!-- Informational text about the dashboard -->
  <p class="MDinfo">
    This is your manager dashboard. Use the shortcuts below to manage notices, categories, and your profile.
  </p>

   <!-- Dashboard grid with shortcut buttons -->
  <div class="MDdashboard-grid">
    <!-- Each link is styled as a button with an icon and label -->
    <a href="admin/manage-notices.php" class="MDbtn"><span class="icon">📢</span> Create or Manage Notices</a>
    <a href="user/my-notices.php" class="MDbtn"><span class="icon">📝</span> My Notices</a>
    <a href="user/bookmarks.php" class="MDbtn"><span class="icon">🔖</span> View My Bookmarks</a>
    <a href="admin/manage-categories.php" class="MDbtn"><span class="icon">📂</span> Manage Categories</a>
    <a href="admin/manage-comments.php" class="MDbtn"><span class="icon">💬</span> Moderate Comments</a>
    <a href="user/profile.php" class="MDbtn"><span class="icon">👤</span> My Profile</a>
  </div>

  </div>

  <!-- Footer section at the bottom of the page -->
  <footer class="MDfooter">
    <!-- Dynamic year using PHP date() -->
    Manager Dashboard © <?= date('Y') ?> — Community Notice Board
  </footer>

  <script>
    // --- Button Fade-in Animation ---
    // Select all elements with class "MDbtn" (dashboard buttons)
    document.querySelectorAll('.MDbtn').forEach((MDbtn, i) => {
      // Initially hide each button and move it slightly down
      MDbtn.style.opacity = 0;
      MDbtn.style.transform = 'translateY(20px)';

      // Use setTimeout to stagger the animation for each button
      setTimeout(() => {
        // Apply transition effect
        MDbtn.style.transition = 'all 0.6s ease';
        // Fade in (opacity 1) and move back to original position
        MDbtn.style.opacity = 1;
        MDbtn.style.transform = 'translateY(0)';
      }, i * 150); // Delay increases with index (creates cascading effect)
    });  
    // pmpofg

    // --- Dark Mode Toggle ---
    document.addEventListener("DOMContentLoaded", function() {
      // Get reference to the toggle button
      const toggleBtn = document.getElementById("toggle-dark");
      // Get reference to the <body> element
      const body = document.body;

      // Check if user previously selected dark mode (stored in localStorage)
      if (localStorage.getItem("theme") === "dark") {
        // Apply dark mode class to body
        body.classList.add("dark-mode");
        // Change button icon to sun (☀️) to indicate light mode toggle
        toggleBtn.textContent = "☀️";
      }

      // Add click event listener to toggle button
      toggleBtn.addEventListener("click", function() {
        // Toggle the "dark-mode" class on body
        body.classList.toggle("dark-mode");

        // If dark mode is active
        if (body.classList.contains("dark-mode")) {
          // Save preference in localStorage
          localStorage.setItem("theme", "dark");
          // Change button icon to sun (☀️)
          toggleBtn.textContent = "☀️";
        } else {
          // Save preference as light mode
          localStorage.setItem("theme", "light");
          // Change button icon back to moon (🌙)
          toggleBtn.textContent = "🌙";
        }
      });
    });
  </script>
</body>
</html>