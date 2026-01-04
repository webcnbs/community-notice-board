<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">
  <header class="LRheader">
    <h1><a href="index.php">Grand Nova</a></h1>
  </header>

  <div class="login-wrapper">
    <div class="login-card">
      <h2>Set New Password</h2>
      <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
      <?php if (!empty($message)) echo '<p class="success">'.htmlspecialchars($message).'</p>'; ?>

      <form method="post" action="route.php?action=reset-password">
        <?php csrf_field(); ?>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label>New Password</label>
        <input type="password" name="password" required placeholder="Enter new password">
        <button type="submit">Update Password</button>
      </form>
    </div>
  </div>
</body>
</html>