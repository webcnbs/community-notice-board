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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Categories | CNB</title>
  <link rel="stylesheet" href="../assets/css/admin-theme.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="dashboard-container">

         <aside class="sidebar" aria-label="Admin navigation">
            <div class="brand">
                <div class="brand-mark">CN</div>
                <div>
                    <h3>CNB Admin</h3>
                    <small>Control panel</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php">Dashboard</a>
                <a href="manage-users.php">Manage Users</a>
                <a href="manage-categories.php">Manage Categories</a>
                <a href="manage-notices.php">Manage Notices</a>
                <a href="manage-comments.php">Manage Comments</a>

                <div class="nav-divider">User Space</div>
                <a href="../user/profile.php">My Profile</a>
                <a href="../user/my-notices.php">My Notices</a>
                <a href="../user/bookmarks.php">Bookmarks</a>
                <a href="../route.php?action=logout" class="logout-btn">Logout</a>
            </nav>
        </aside>


            
            <div class="mobile-only" data-sidebar-overlay style="position:fixed;inset:0;z-index:30;display:none;"></div>

    <main class="main-content">

      <section class="card mb-2">
        <div class="card-header">
          <h3>Add Category</h3>
          <p>Choose a name, optional description, and a color label.</p>
        </div>
        <div class="card-body">
          <form method="post" class="form-grid">
            <div class="col-6">
              <label>Name</label>
              <input type="text" name="name" required placeholder="e.g., Maintenance">
            </div>
            <div class="col-6">
              <label>Color</label>
              <input type="color" name="color_code" value="#5b8cff" style="height:44px;padding:6px;">
            </div>
            <div class="col-12">
              <label>Description</label>
              <input type="text" name="description" placeholder="Short description (optional)">
            </div>
            <div class="col-12">
              <div class="form-actions">
                <button type="submit">Add Category</button>
                <a href="dashboard.php" class="btn secondary">Back</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <div class="card-header">
          <h3>Existing Categories</h3>
          <p>Delete categories you no longer need.</p>
        </div>
        <div class="card-body">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Color</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categories as $c): ?>
                  <tr>
                    <td><strong><?php echo htmlspecialchars($c['name']); ?></strong></td>
                    <td class="muted"><?php echo htmlspecialchars($c['description']); ?></td>
                    <td>
                      <span class="badge" style="border-color: <?php echo htmlspecialchars($c['color_code']); ?>33; background: <?php echo htmlspecialchars($c['color_code']); ?>22; color: #fff;">
                        <?php echo htmlspecialchars($c['color_code']); ?>
                      </span>
                    </td>
                    <td>
                      <div class="table-actions">
                        <form method="post">
                          <input type="hidden" name="category_id" value="<?php echo $c['category_id']; ?>">
                          <button name="action" value="delete" class="danger">Delete</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

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
          <div class="mt-3">
            <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back to Dashboard</a>
          </div>
        </div>
      </section>

    </main>
  </div>

  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
