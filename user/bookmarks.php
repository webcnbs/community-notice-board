<?php
// user/bookmarks.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Bookmark.php';

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

$bookmarkModel = new Bookmark();
$bookmarks = $bookmarkModel->list($_SESSION['user']['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Bookmarks</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>My Bookmarks</h2>

  <!-- ✅ Feedback messages -->
  <?php if (isset($_GET['added'])): ?>
    <p class="success">Notice bookmarked successfully!</p>
  <?php endif; ?>
  <?php if (isset($_GET['removed'])): ?>
    <p class="success">Bookmark removed successfully!</p>
  <?php endif; ?>

  <?php if (empty($bookmarks)): ?>
    <p class="info">You haven't bookmarked any notices yet.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($bookmarks as $b): ?>
        <li>
          <a href="../view-notice.php?id=<?php echo $b['notice_id']; ?>">
            <?php echo htmlspecialchars($b['title']); ?>
          </a>
          <small><?php echo $b['created_at']; ?></small>
          <form method="post" action="../api/bookmarks.php" style="display:inline;">
            <input type="hidden" name="notice_id" value="<?php echo $b['notice_id']; ?>">
            <button type="submit" name="action" value="remove" class="danger">Remove</button>
          </form>
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