<?php
// admin/manage-categories.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Category.php';

// Restrict access to admins and managers
require_role(['admin','manager']);

$categoryModel = new Category();

// Handle form submissions
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

// Fetch categories for display
$categories = $categoryModel->all();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>Manage Categories</h2>

  <form method="post">
    <label>Name</label><input type="text" name="name" required>
    <label>Description</label><input type="text" name="description">
    <label>Color</label><input type="color" name="color_code" value="#888888">
    <button type="submit">Add Category</button>
  </form>
  <hr>
  <ul>
    <?php foreach ($categories as $c): ?>
      <li style="color:<?php echo htmlspecialchars($c['color_code']); ?>">
        <?php echo htmlspecialchars($c['name']); ?> - <?php echo htmlspecialchars($c['description']); ?>
        <form method="post" style="display:inline;">
          <input type="hidden" name="category_id" value="<?php echo $c['category_id']; ?>">
          <button name="action" value="delete" class="danger">Delete</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

<?php
$role = $_SESSION['user']['role'] ?? '';
if ($role === 'admin') {
    $dashboardUrl = 'dashboard.php';
} elseif ($role === 'manager') {
    $dashboardUrl = '../index2.php';
} else {
    $dashboardUrl = '../index.php';
}
?>
<p class="mt-2">
  <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back to Dashboard</a>
</p>

</body>
</html>