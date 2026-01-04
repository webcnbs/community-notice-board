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
  <title>Manage Notices</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/MDstyle.css">

</head>

<body class="MDbody">

  <header class="MDheader">
    <h1>Manage Notices</h1>

    <nav class="MDnav">
      <a class="MDbtn secondary" href="<?= $dashboardUrl; ?>">Back</a>
      <a class="MDbtn danger" href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
    </nav>
  </header>

  <div class="container">

    <?php if (isset($_GET['created'])): ?>
      <p class="success">Notice created successfully!</p>
    <?php endif; ?>

    <!-- Create Notice -->
    <section class="card">
      <h2 style="margin-top:0;">Create Notice</h2>

      <form method="post" action="<?= BASE_URL ?>/route.php?action=manage-notices">
        <?php csrf_field(); ?>
        <input type="hidden" name="action" value="create">

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Content</label>
        <textarea name="content" required></textarea>

        <label>Category</label>
        <select name="category_id" required>
          <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['category_id']; ?>">
              <?= htmlspecialchars($c['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label>Priority</label>
        <select name="priority">
          <option>Low</option>
          <option>Medium</option>
          <option>High</option>
        </select>

        <label>Expiry Date</label>
        <input type="date" name="expiry_date">

        <button type="submit">Create Notice</button>
      </form>
    </section>

    <!-- Existing Notices -->
    <section class="card mt-2">
      <h2 style="margin-top:0;">Existing Notices</h2>

      <?php if (empty($notices)): ?>
        <p>No notices found.</p>
      <?php else: ?>
        <div class="admin-list">
          <?php foreach ($notices as $n): ?>
            <div class="admin-item">
              <span>
                <?= htmlspecialchars($n['title']); ?>
                <small>
                  (<?= htmlspecialchars($n['category_name'] ?? 'Uncategorized'); ?>)
                </small>
              </span>

              <div class="admin-item-actions">
                <form method="post" action="<?= BASE_URL ?>/route.php?action=manage-notices">
                  <?php csrf_field(); ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="notice_id" value="<?= (int)$n['notice_id']; ?>">
                  <button type="submit" class="danger">Delete</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

  </div>

</body>
</html>
