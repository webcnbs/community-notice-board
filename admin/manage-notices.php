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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Notices | CNB</title>
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

      <!-- ✅ Success messages -->
      <?php if (isset($_GET['created'])): ?>
        <div class="alert success">Notice created successfully!</div>
      <?php endif; ?>
      <?php if (isset($_GET['updated'])): ?>
        <div class="alert success">Notice updated successfully!</div>
      <?php endif; ?>
      <?php if (isset($_GET['deleted'])): ?>
        <div class="alert success">Notice deleted successfully!</div>
      <?php endif; ?>

      <section class="card mb-2">
        <div class="card-header">
          <h3>Create Notice</h3>
          <p>Fill the form and publish a new notice.</p>
        </div>
        <div class="card-body">
          <form method="post" action="../route.php?action=manage-notices" class="form-grid">
            <?php csrf_field(); ?>
            <input type="hidden" name="action" value="create">

            <div class="col-12">
  <label>Title</label>
  <input type="text" name="title" placeholder="Enter notice title" required>
</div>

            <div class="col-12">
              <label>Content</label>
              <textarea name="content" required placeholder="Write the notice content..."></textarea>
            </div>

            <div class="col-6">
              <label>Category</label>
              <select name="category_id" required>
                <?php foreach ($categories as $c): ?>
                  <option value="<?php echo $c['category_id']; ?>">
                    <?php echo htmlspecialchars($c['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-3">
              <label>Priority</label>
              <select name="priority">
                <option>Low</option>
                <option selected>Medium</option>
                <option>High</option>
              </select>
            </div>

            <div class="col-3">
              <label>Expiry</label>
              <input type="date" name="expiry_date">
            </div>

            <div class="col-12">
              <div class="form-actions">
                <button type="submit">Create Notice</button>
                <a href="dashboard.php" class="btn secondary">Back</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <div class="card-header">
          <h3>Existing Notices</h3>
          <p>Delete notices that are no longer needed.</p>
        </div>
        <div class="card-body">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Category</th>
                  <th>Priority</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($notices as $n): ?>
                  <tr>
                    <td><strong><?php echo htmlspecialchars($n['title']); ?></strong></td>
                    <td class="muted"><?php echo htmlspecialchars($n['category_name'] ?? 'Uncategorized'); ?></td>
                    <td>
                      <?php 
                        $p = strtolower($n['priority'] ?? 'medium');
                        $cls = 'badge';
                        if ($p === 'high') $cls .= ' danger';
                        elseif ($p === 'low') $cls .= '';
                        else $cls .= ' warning';
                      ?>
                      <span class="<?php echo $cls; ?>"><?php echo htmlspecialchars($n['priority']); ?></span>
                    </td>
                    <td>
                      <div class="table-actions">
                        <form method="post" action="../route.php?action=manage-notices">
                          <?php csrf_field(); ?>
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="notice_id" value="<?php echo $n['notice_id']; ?>">
                          <button type="submit" class="danger">Delete</button>
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
    $dashboardUrl = 'dashboard.php'; // ✅ corrected path
} elseif ($role === 'manager') {
    $dashboardUrl = '../index2.php';
} else {
    $dashboardUrl = '../index.php';
}
?>
          <div class="mt-3">
            <a href="<?php echo $dashboardUrl; ?>" class="btn secondary">← Back</a>
          </div>
        </div>
      </section>

    </main>
  </div>

  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
