<?php
// admin/manage-notices.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Notice.php';

require_role(['manager','admin']);

$categoryModel = new Category();
$categories = $categoryModel->all();

$noticeModel = new Notice();
$notices = $noticeModel->all();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Notices</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/MDstyle.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"> 
</head>
<body>
  <h2>Manage Notices</h2>

  <?php if (isset($_GET['created'])): ?>
    <p class="success">Notice created successfully!</p>
  <?php endif; ?>

  <form method="post" action="<?= BASE_URL ?>/route.php?action=manage-notices">
    <?php csrf_field(); ?>
    <input type="hidden" name="action" value="create">
    <label>Title</label><input name="title" required>
    <label>Content</label><textarea name="content" required></textarea>
    <label>Category</label>
    <select name="category_id" required>
      <?php foreach ($categories as $c): ?>
        <option value="<?php echo $c['category_id']; ?>">
          <?php echo htmlspecialchars($c['name']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label>Priority</label>
    <select name="priority">
      <option>Low</option>
      <option>Medium</option>
      <option>High</option>
    </select>
    <label>Expiry</label><input type="date" name="expiry_date">
    <button type="submit">Create Notice</button>
  </form>

  <hr>
  <h3>Existing Notices</h3>
  <div class="admin-list">
    <?php foreach ($notices as $n): ?>
      <div class="admin-item">
        <span>
          <?php echo htmlspecialchars($n['title']); ?>
          (<?php echo htmlspecialchars($n['category_name'] ?? 'Uncategorized'); ?>)
        </span>
        <form method="post" action="<?= BASE_URL ?>/route.php?action=manage-notices"> 
          <?php csrf_field(); ?>
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="notice_id" value="<?php echo $n['notice_id']; ?>">
          <button type="submit" class="danger">Delete</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php
$role = $_SESSION['user']['role'] ?? '';

// We use BASE_URL to ensure we start from the project root
if ($role === 'admin') {
    // Use the Router instead of the direct file path
    $dashboardUrl = BASE_URL . '/route.php?action=admin-dashboard'; 
} elseif ($role === 'manager') {
    $dashboardUrl = BASE_URL . '/index2.php';
} else {
    $dashboardUrl = BASE_URL . '/index.php';
}
?>

<p class="mt-2">
    <a href="<?= $dashboardUrl; ?>" class="btn secondary">← Back to Dashboard</a>
</p>
</body>
</html>