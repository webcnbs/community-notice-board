<!-- Omar -->
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

<meta charset="UTF-8"><title>Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-bg">
   <header class="LRheader">
    <h1>Community Notice Board</h1>
    <nav>
      <p>Without an account? <a href="register.php">Register</a></p>
    </nav>
  </header>

<div class="login-wrapper">
    <div class="login-card">
      <h2>Login</h2>
      <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?> <!-- Prevents XSS -->
      <form method="post" action="route.php?action=login">
        <?php csrf_field(); ?>
        <label>Email</label>
        <input type="email" name="email" required placeholder="Enter your email">
        <label>Password</label>
        <input type="password" name="password" required placeholder="Enter your password">
        <label class="remember">
          <input class="Cbox" type="checkbox" name="remember"> Remember me</label>
        <button type="submit">Login</button>
        <div class="login-footer">
          <a href="#">Forgot Password?</a><br>
        </div>
      </form>
    </div>
  </div>
</body>
</html>