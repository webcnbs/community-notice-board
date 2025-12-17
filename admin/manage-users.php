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
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2>Manage Users</h2>

  <div class="admin-list">
    <?php foreach ($users as $u): ?>
      <div class="admin-item">
        <span>
          <?php echo htmlspecialchars($u['username']); ?>
          (<?php echo htmlspecialchars($u['email']); ?>) -
          Status: <?php echo htmlspecialchars($u['status']); ?> •
          Role: <?php echo htmlspecialchars($u['role']); ?>
        </span>
        <div class="admin-item-actions">
          <!-- Approve/Disable -->
          <form method="post" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
            <button name="action" value="approve">Approve</button>
            <button name="action" value="disable" class="danger">Disable</button>
          </form>

          <!-- Role Assignment -->
          <form method="post" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
            <select name="role">
              <option value="resident" <?php if ($u['role'] === 'resident') echo 'selected'; ?>>Resident</option>
              <option value="manager" <?php if ($u['role'] === 'manager') echo 'selected'; ?>>Manager</option>
              <option value="admin" <?php if ($u['role'] === 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            <button name="action" value="update-role">Update Role</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <p><a href="dashboard.php" class="btn secondary">← Back to Dashboard</a></p>
</body>
</html>