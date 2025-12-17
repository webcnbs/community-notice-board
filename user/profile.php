<?php
// user/profile.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

session_name(SESSION_NAME);
session_start();

// Restrict access to residents, managers, and admins
require_role(['resident','manager','admin']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>My Profile</h2>
  <div class="card">
    <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['user']['username']); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>
    <p><strong>Email:</strong> (hidden for demo — fetch from DB if needed)</p>
  </div>

<?php
$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = '../admin/dashboard.php'; // ✅ correct path
} elseif ($role === 'manager') {
    $dashboardUrl = '../index2.php'; // ✅ manager dashboard
} else {
    $dashboardUrl = '../index.php'; // ✅ resident home
}
?>
<p class="mt-2">
  <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back</a>
</p>
</body>
</html>