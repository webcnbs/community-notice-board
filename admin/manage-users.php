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
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/MDstyle.css">
</head>

<body class="MDbody">

  <header class="MDheader">
    <h1>Manage Users</h1>

    <nav class="MDnav">
      <a class="MDbtn secondary" href="<?= BASE_URL ?>/route.php?action=admin-dashboard">Back</a>
      <a class="MDbtn danger" href="<?= BASE_URL ?>/route.php?action=logout">Logout</a>
    </nav>
  </header>

  <div class="container">
    <section class="card">
      <h2 class="MDh2" style="margin-top:0;">Users</h2>

      <div class="admin-list">
        <?php foreach ($users as $u): ?>
          <div class="admin-item">
            <span>
              <?= htmlspecialchars($u['username']) ?> (<?= htmlspecialchars($u['role']) ?>)
            </span>

            <div class="admin-item-actions" style="display:flex; flex-direction:column; gap:10px;">

  <!-- Approve / Disable -->
  <form method="post" action="<?= BASE_URL ?>/admin/manage-users.php"
        style="display:flex; gap:10px;">
    <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
    <button name="action" value="approve">Approve</button>
    <button name="action" value="disable" class="danger">Disable</button>
  </form>

  <!-- Role update -->
  <form method="post" action="<?= BASE_URL ?>/admin/manage-users.php"
        style="display:flex; gap:10px; align-items:center;">
    <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">

    <select name="role" style="flex:1;">
      <option value="resident" <?= $u['role'] === 'resident' ? 'selected' : '' ?>>Resident</option>
      <option value="manager" <?= $u['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
      <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <button name="action" value="update-role">Update</button>
  </form>

</div>

          </div>
        <?php endforeach; ?>
      </div>

    </section>
  </div>

</body>
</html>
