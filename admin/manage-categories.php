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
}
$categories = $categoryModel->all();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
  <h2>Manage Categories</h2>

  <form method="post" action="<?= BASE_URL ?>/admin/manage-categories.php">
    <label>Name</label><input type="text" name="name" required>
    <label>Description</label><input type="text" name="description">
    <label>Color</label><input type="color" name="color_code" value="#888888">
    <button type="submit">Add Category</button>
  </form>
  <hr>
  <ul>
    <?php foreach ($categories as $c): ?>
      <li style="color:<?php echo htmlspecialchars($c['color_code']); ?>">
        <?php echo htmlspecialchars($c['name']); ?>
        <form method="post" style="display:inline;" action="<?= BASE_URL ?>/admin/manage-categories.php">
          <input type="hidden" name="category_id" value="<?php echo $c['category_id']; ?>">
          <button name="action" value="delete" class="danger">Delete</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

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