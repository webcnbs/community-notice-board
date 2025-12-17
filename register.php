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
<body>

  <header>
    <h1>Community Notice Board</h1>
    <nav>
       <p>Already have an account?<a href="login.php">Login</a></p>
    </nav>
  </header>

  <div class="container">
      <h2 class="center">Register</h2>

      <!-- ✅ Show error or success messages -->
      <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <?php if (!empty($message)): ?>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <form method="post" action="route.php?action=register" class="form-card">
        <?php csrf_field(); ?>

        <label for="username">Username</label>
        <input id="username" type="text" name="username" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required minlength="8">

        <button type="submit" class="btn primary">Register</button>
      </form>
  </div>
</body>
</html>