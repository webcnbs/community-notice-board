<?php
// admin/manage-users.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';

// Restrict access to admins only
require_role(['admin']);

$userModel = new User();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($userId && $action === 'approve') {
        $userModel->updateStatus($userId, 'active');
    } elseif ($userId && $action === 'disable') {
        $userModel->updateStatus($userId, 'disabled');
    } elseif ($userId && $action === 'update-role' && !empty($_POST['role'])) {
        $role = $_POST['role'];
        // ✅ Only allow valid roles
        if (in_array($role, ['admin', 'manager', 'resident'])) {
            $userModel->updateRole($userId, $role);
        }
    }
}

// Fetch all users for display
$users = $userModel->all();
?>
<!DOCTYPE html>
<html lang="en">
<head>


<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin-theme.css">

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users | CNB</title>
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

      <section class="card">
        <div class="card-header">
          <h3>All Users</h3>
          <p>Use the actions on the right to manage each account.</p>
        </div>

        <div class="card-body">
          <div class="admin-list">
            <?php foreach ($users as $u): ?>
              <div class="admin-item">
                <div>
                  <div style="font-weight:800;font-size:1.02rem;">
                    <?php echo htmlspecialchars($u['username']); ?>
                  </div>
                  <div class="kv mt-1">
                    <div class="k">Email</div>
                    <div class="v"><?php echo htmlspecialchars($u['email']); ?></div>
                  </div>
                  <div class="kv mt-1">
                    <div class="k">Status</div>
                    <div class="v"><?php echo htmlspecialchars($u['status']); ?></div>
                  </div>
                  <div class="kv mt-1">
                    <div class="k">Role</div>
                    <div class="v"><?php echo htmlspecialchars($u['role']); ?></div>
                  </div>
                </div>

                <div class="admin-item-actions">
                  <!-- Approve/Disable -->
                  <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                    <div class="form-actions">
                      <button name="action" value="approve">Approve</button>
                      <button name="action" value="disable" class="danger">Disable</button>
                    </div>
                  </form>

                  <!-- Role Assignment -->
                  <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                    <div class="form-actions">
                      <select name="role" aria-label="Select role">
                        <option value="resident" <?php if ($u['role'] === 'resident') echo 'selected'; ?>>Resident</option>
                        <option value="manager" <?php if ($u['role'] === 'manager') echo 'selected'; ?>>Manager</option>
                        <option value="admin" <?php if ($u['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                      </select>
                      <button name="action" value="update-role" class="secondary">Update Role</button>
                    </div>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="mt-3">
            <a href="dashboard.php" class="btn secondary">← Back to Dashboard</a>
          </div>
        </div>
      </section>

    </main>
  </div>

  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
