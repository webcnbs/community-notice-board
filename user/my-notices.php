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

$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = BASE_URL . '/route.php?action=admin-dashboard';
} elseif ($role === 'manager') {
    $dashboardUrl = BASE_URL . '/index2.php';
} else {
    $dashboardUrl = BASE_URL . '/index.php';
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Notices</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/MDstyle.css">
</head>

<body class="MDbody">

  <header class="MDheader">
    <h1>My Notices</h1>

    <nav class="MDnav">
      <a class="MDbtn secondary" href="<?= $dashboardUrl; ?>">Back</a>
      <a class="MDbtn danger" href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
    </nav>
  </header>

  <div class="container">

    <section class="card">
      <h2 style="margin-top:0;">Your Notices</h2>

      <?php if (empty($myNotices)): ?>
        <p class="info">You haven’t created any notices yet.</p>
      <?php else: ?>
        <ul id="notice-list">
          <?php foreach ($myNotices as $n): ?>
            <li>
              <a href="<?= BASE_URL ?>/view-notice.php?id=<?= (int)$n['notice_id']; ?>">
                <?= htmlspecialchars($n['title']); ?>
              </a>
              <small>
                <?= htmlspecialchars($n['created_at']); ?> •
                <?= htmlspecialchars($n['priority']); ?>
              </small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>

  </div>

</body>
</html>