<?php
// admin/manage-users.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/User.php';

require_role(['admin']);
$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($userId && $action === 'approve') {
        $userModel->updateStatus($userId, 'active');
    } elseif ($userId && $action === 'disable') {
        $userModel->updateStatus($userId, 'disabled');
    } elseif ($userId && $action === 'update-role' && !empty($_POST['role'])) {
        $role = $_POST['role'];
        if (in_array($role, ['admin', 'manager', 'resident'])) {
            $userModel->updateRole($userId, $role);
        }
    }
}
$users = $userModel->all();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
  <h2>Manage Users</h2>

  <div class="admin-list">
    <?php foreach ($users as $u): ?>
      <div class="admin-item">
        <span>
          <?php echo htmlspecialchars($u['username']); ?> (<?php echo htmlspecialchars($u['role']); ?>)
        </span>
        <div class="admin-item-actions">
          <form method="post" action="<?= BASE_URL ?>/admin/manage-users.php" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
            <button name="action" value="approve">Approve</button>
            <button name="action" value="disable" class="danger">Disable</button>
          </form>

          <form method="post" action="<?= BASE_URL ?>/admin/manage-users.php" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
            <select name="role">
              <option value="resident" <?= $u['role'] === 'resident' ? 'selected' : '' ?>>Resident</option>
              <option value="manager" <?= $u['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
              <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
            <button name="action" value="update-role">Update Role</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <p><a href="<?= BASE_URL ?>/route.php?action=admin-dashboard" class="btn secondary">← Back to Dashboard</a></p>
</body>
</html>