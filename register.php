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
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">
  <header class="LRheader">
    <h1>Community Notice Board</h1>
    <nav>
      <p>Without an account? <a href="login.php">Login</a></p>
    </nav>
  </header>

  <div class="login-wrapper">
    <div class="login-card">
      <h2>Register</h2>

      <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <?php if (!empty($message)): ?>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <form method="post" action="route.php?action=register">
        <?php csrf_field(); ?>

        <label for="username">Username</label>
        <input id="username" type="text" name="username" required placeholder="Enter your username">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" required placeholder="Enter your email">

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required minlength="8" placeholder="Create a password">

        <button type="submit">Register</button>
      </form>
    </div>
  </div>
</body>
</html>