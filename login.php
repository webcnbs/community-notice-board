<?php
require_once __DIR__ . '/includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

require_once __DIR__ . '/includes/csrf.php';
?>
<!DOCTYPE html>
<html>
<head>
  <style>
    a:link, a:visited, a:hover, a:active {
      color: #000000ff; /* Sets the link color to red (you can use any valid color value) */
      text-decoration: none; /* Removes the underline from the link */
    }
  </style>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <header>
    <a href="index.php"><h1>Community Notice Board</h1></a>
    <nav>
        <p><a href="register.php" style="text-decoration: underline">Register</a></p>
    </nav>
  </header>

  <div class="container">
    <h2 class="center">Login</h2>

    <form method="post" action="route.php?action=login" class="form-card">
      <?php csrf_field(); ?>
      <label>Email</label><input type="email" name="email" required>
      <label>Password</label><input type="password" name="password" required>
      <label><input type="checkbox" name="remember"> Remember me</label>
      <button type="submit" class="btn primary">Login</button>
    </form>

    <div class="center">
      <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?> <!-- htmlsepcialchars prevents XSS -->
    </div>

  </div>
</body>
</html>