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
<title>Login</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">
   <header class="LRheader">
      <h1><a href="index.php"><img class="LRlogo" src="assets/images/logo.png" alt=""> Grand Nova</a></h1>
      <nav>
        <p>Without an account? <a href="register.php">Register</a></p>
      </nav>
   </header>

   <div class="login-wrapper">
     <div class="login-card">
       <h2>Login</h2>
       <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
       <?php if (!empty($message)) echo '<p class="success">'.htmlspecialchars($message).'</p>'; ?>

       <form method="post" action="route.php?action=login">
         <?php csrf_field(); ?>
         <label for="email">Email</label>
         <input id="email" type="email" name="email" required placeholder="Enter your email">

         <label for="password">Password</label>
         <input id="password" type="password" name="password" required placeholder="Enter your password">

         <label class="remember">
           <input class="Cbox" type="checkbox" name="remember"> Remember me
         </label>

         <button type="submit">Login</button>

         <div class="login-footer">
           <a href="forgot-password.php">Forgot Password?</a><br>
         </div>
       </form>
     </div>
   </div>
</body>
</html>