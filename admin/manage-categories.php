<?php
// admin/manage-categories.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Category.php';

require_role(['admin','manager']);
$categoryModel = new Category();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $categoryModel->delete((int)$_POST['category_id']);
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $color = $_POST['color_code'] ?? '#888888';
        $categoryModel->create($name, $description, $color);
    }

    // simple redirect (prevents re-submit on refresh)
    header("Location: " . BASE_URL . "/admin/manage-categories.php");
    exit;
}

$categories = $categoryModel->all();

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
  <title>Manage Categories</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/MDstyle.css">

</head>

<body class="MDbody">

  <header class="MDheader">
    <h1>Manage Categories</h1>

    <nav class="MDnav">
      <a class="MDbtn secondary" href="<?= $dashboardUrl; ?>">Back</a>
      <a class="MDbtn danger" href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
    </nav>
  </header>

  <div class="container">

    <section class="card">
      <h2 style="margin-top:0;">Add  Category</h2>

      <form method="post" action="<?= BASE_URL ?>/admin/manage-categories.php">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Description</label>
        <input type="text" name="description">

        <label>Color</label>
        <input type="color" name="color_code" value="#888888">

        <button type="submit">Add Category</button>
      </form>
    </section>

    <section class="card mt-2">
      <h2 style="margin-top:0;">All Categories</h2>

      <?php if (empty($categories)): ?>
        <p>No categories yet.</p>
      <?php else: ?>
        <div class="admin-list">
          <?php foreach ($categories as $c): ?>
            <div class="admin-item">
              <span>
                <strong style="color:<?= htmlspecialchars($c['color_code']) ?>;">
                  <?= htmlspecialchars($c['name']) ?>
                </strong>
                <?php if (!empty($c['description'])): ?>
                  <small style="display:block;">
                    <?= htmlspecialchars($c['description']) ?>
                  </small>
                <?php endif; ?>
              </span>

              <div class="admin-item-actions">
                <form method="post" action="<?= BASE_URL ?>/admin/manage-categories.php" style="display:inline;">
                  <input type="hidden" name="category_id" value="<?= (int)$c['category_id']; ?>">
                  <button name="action" value="delete" class="danger">Delete</button>
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
