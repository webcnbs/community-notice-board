<?php
// admin/manage-notices.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Notice.php';

// Restrict access to managers and admins
require_role(['manager','admin']);

// Fetch categories for the dropdown
$categoryModel = new Category();
$categories = $categoryModel->all();

// Fetch existing notices
$noticeModel = new Notice();
$notices = $noticeModel->all();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Notices</title>
  <link rel="stylesheet" href="/assets/css/style.css"> <!-- Front end fix -->
</head>
<body>
  <h2>Manage Notices</h2>

  <!-- ✅ Success messages -->
  <?php if (isset($_GET['created'])): ?>
    <p class="success">Notice created successfully!</p>
  <?php endif; ?>
  <?php if (isset($_GET['updated'])): ?>
    <p class="success">Notice updated successfully!</p>
  <?php endif; ?>
  <?php if (isset($_GET['deleted'])): ?>
    <p class="success">Notice deleted successfully!</p>
  <?php endif; ?>

  <form method="post" action="../route.php?action=manage-notices">
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
          (<?php echo htmlspecialchars($n['category_name'] ?? 'Uncategorized'); ?> • <?php echo htmlspecialchars($n['priority']); ?>)
        </span>
        <form method="post" action="../route.php?action=manage-notices">
          <?php csrf_field(); ?>
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="notice_id" value="<?php echo $n['notice_id']; ?>">
          <button type="submit" class="danger">Delete</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- ✅ Back button at the bottom -->
<?php
  $role = $_SESSION['user']['role'] ?? '';
  if ($role === 'admin') {
      $dashboardUrl = '../route.php?action=admin-dashboard'; 
  } elseif ($role === 'manager') {
      $dashboardUrl = '../route.php?action=index2'; 
  } else {
      $dashboardUrl = '../index.php';
  }
  ?>

  <p class="mt-2">
    <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back</a>
  </p>
</body>
</html>