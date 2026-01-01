<?php
// user/my-notices.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/database.php';

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

require_once __DIR__ . '/../models/Notice.php';

$pdo = Database::getInstance()->pdo();
$stmt = $pdo->prepare("SELECT * FROM notices WHERE user_id=? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user']['user_id']]);
$myNotices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Notices</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>My Notices</h2>

  <?php if (empty($myNotices)): ?>
    <p class="info">You haven't created any notices yet.</p>
  <?php else: ?>
    <ul class="notice-list">
      <?php foreach ($myNotices as $n): ?>
        <li>
          <a href="../view-notice.php?id=<?php echo $n['notice_id']; ?>">
            <?php echo htmlspecialchars($n['title']); ?>
          </a>
          <small>
            <?php echo htmlspecialchars($n['created_at']); ?> • 
            <?php echo htmlspecialchars($n['priority']); ?>
          </small>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

<?php
$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = '../admin/dashboard.php'; // ✅ corrected path
} elseif ($role === 'manager') {
    $dashboardUrl = '../index2.php';
} else {
    $dashboardUrl = '../index.php';
}
?>
<p class="mt-2">
  <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back </a>
</p>
</body>
</html>