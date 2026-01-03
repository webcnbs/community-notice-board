<?php 
// admin/dashboard.php 
require_once __DIR__ . '/../includes/config.php'; 
require_once __DIR__ . '/../includes/session.php'; 
require_once __DIR__ . '/../includes/functions.php'; 
require_once __DIR__ . '/../models/Category.php'; 
require_once __DIR__ . '/../models/AuditLog.php'; 

require_role(['admin']); 

$categoryModel = new Category(); 
$categories = $categoryModel->all(); 

$auditLogModel = new AuditLog(); 
$logs = $auditLogModel->recent(10); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
  <meta charset="UTF-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | CNB</title> 
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin-theme.css"> 
  
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

      <section class="stats-grid">
        <div class="stat-card">
          <div class="stat-top">
            <span class="stat-label">Total Categories</span>
            <span class="badge info">Live</span>
          </div>
          <div class="stat-value"><?php echo count($categories); ?></div>
          <div class="muted">All notice categories in the system.</div>
        </div>

        <div class="stat-card">
          <div class="stat-top">
            <span class="stat-label">System Status</span>
            <span class="badge success">Online</span>
          </div>
          <div class="stat-value">100%</div>
          <div class="muted">Services responding normally.</div>
        </div>

        <div class="stat-card">
          <div class="stat-top">
            <span class="stat-label">Pending Notices</span>
            <span class="badge warning">Review</span>
          </div>
          <div class="stat-value">12</div>
          <div class="muted">Waiting for approval / action.</div>
        </div>
      </section>

      <section class="card">
        <div class="card-header">
          <h3>Recent Audit Logs</h3>
          <p>Latest admin actions recorded by the system.</p>
        </div>
        <div class="card-body">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Action</th>
                  <th>User</th>
                  <th>Timestamp</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($logs as $log): ?> 
                  <tr>
                    <td><strong><?php echo htmlspecialchars($log['action']); ?></strong></td>
                    <td><?php echo htmlspecialchars($log['username'] ?? 'System'); ?></td>
                    <td><?php echo $log['timestamp']; ?></td>
                  </tr>
                <?php endforeach; ?> 
              </tbody>
            </table>
          </div>
          <p class="mt-2 muted">Tip: Use “Manage Users / Notices” to take action quickly.</p>
        </div>
      </section>

    </main>
  </div>

  <script src="../assets/js/dashboard.js"></script>
</body> 
</html>
