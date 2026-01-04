<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">
  <header class="LRheader">
    <h1><a href="index.php">Grand Nova</a></h1>
  </header>

  <div class="login-wrapper">
    <div class="login-card">
      <h2>Reset Password</h2>
      <form method="post" action="route.php?action=forgot-password">
        <?php csrf_field(); ?>
        <label>Email</label>
        <input type="email" name="email" required placeholder="Enter your email">
        <button type="submit">Send Reset Link</button>
      </form>
    </div>
  </div>
</body>
</html>